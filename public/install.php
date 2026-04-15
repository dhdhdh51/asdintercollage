<?php
/**
 * School ERP - Web Installer
 * ---------------------------
 * Upload all project files to your cPanel hosting, then visit:
 *   https://yourdomain.com/install.php
 *
 * IMPORTANT: Delete this file after installation is complete!
 */

define('INSTALL_LOCK_FILE', __DIR__ . '/../storage/installed.lock');
define('BASE_PATH', dirname(__DIR__));
define('INSTALLER_VERSION', '1.0.0');

// ─── Block re-install if already done ────────────────────────────────────────
if (file_exists(INSTALL_LOCK_FILE) && !isset($_GET['force'])) {
    die(render_message(
        'Already Installed',
        'School ERP is already installed. <a href="/">Go to Homepage</a>',
        'success'
    ));
}

session_start();
$step = (int) ($_GET['step'] ?? 1);
$errors = [];
$success = [];

// ─── Step Actions ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 2) {
        handle_db_step();
    } elseif ($step === 3) {
        handle_app_step();
    } elseif ($step === 4) {
        handle_install_step();
    }
}

// ─── Handlers ─────────────────────────────────────────────────────────────────
function handle_db_step() {
    $dbConn = $_POST['db_connection'] ?? 'mysql';
    $_SESSION['db_connection'] = $dbConn;

    if ($dbConn === 'sqlite') {
        $_SESSION['db_database'] = BASE_PATH . '/database/database.sqlite';
        if (!file_exists($_SESSION['db_database'])) {
            touch($_SESSION['db_database']);
        }
        header('Location: install.php?step=3');
        exit;
    }

    // MySQL validation
    $host     = trim($_POST['db_host'] ?? '127.0.0.1');
    $port     = trim($_POST['db_port'] ?? '3306');
    $name     = trim($_POST['db_name'] ?? '');
    $user     = trim($_POST['db_user'] ?? '');
    $pass     = $_POST['db_pass'] ?? '';

    if (!$name || !$user) {
        $_SESSION['errors'] = ['Database name and username are required.'];
        header('Location: install.php?step=2');
        exit;
    }

    try {
        $pdo = new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
            $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $_SESSION['db_host']     = $host;
        $_SESSION['db_port']     = $port;
        $_SESSION['db_name']     = $name;
        $_SESSION['db_user']     = $user;
        $_SESSION['db_pass']     = $pass;
        header('Location: install.php?step=3');
        exit;
    } catch (PDOException $e) {
        $_SESSION['errors'] = ['Could not connect to MySQL: ' . $e->getMessage()];
        header('Location: install.php?step=2');
        exit;
    }
}

function handle_app_step() {
    $appName = trim($_POST['app_name'] ?? 'School ERP');
    $appUrl  = rtrim(trim($_POST['app_url'] ?? ''), '/');
    $adminEmail = trim($_POST['admin_email'] ?? '');
    $adminPass  = $_POST['admin_pass'] ?? '';
    $adminName  = trim($_POST['admin_name'] ?? 'Administrator');

    $errors = [];
    if (!$appUrl) $errors[] = 'App URL is required.';
    if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid admin email is required.';
    if (strlen($adminPass) < 6) $errors[] = 'Admin password must be at least 6 characters.';

    if ($errors) {
        $_SESSION['errors'] = $errors;
        header('Location: install.php?step=3');
        exit;
    }

    $_SESSION['app_name']    = $appName;
    $_SESSION['app_url']     = $appUrl;
    $_SESSION['admin_email'] = $adminEmail;
    $_SESSION['admin_pass']  = $adminPass;
    $_SESSION['admin_name']  = $adminName;

    header('Location: install.php?step=4');
    exit;
}

function handle_install_step() {
    $log = [];

    try {
        // 1. Generate a secure app key
        $appKey = 'base64:' . base64_encode(random_bytes(32));

        // 2. Write config/local.php (replaces .env — pure PHP config)
        $log[] = write_local_config($appKey);

        // 3. Bootstrap Laravel (config/local.php is now loaded by config files)
        require BASE_PATH . '/vendor/autoload.php';
        $app    = require BASE_PATH . '/bootstrap/app.php';
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

        // 4. Run migrations
        ob_start();
        $kernel->call('migrate', ['--force' => true]);
        ob_get_clean();
        $log[] = '✔ Migrations complete';

        // 5. Run seeds
        ob_start();
        $kernel->call('db:seed', ['--force' => true]);
        ob_get_clean();
        $log[] = '✔ Database seeded (default settings, classes, subjects)';

        // 6. Create/update admin user
        $userModel = $app->make(\App\Models\User::class);
        $existing  = $userModel->where('role', 'admin')->first();
        $adminData = [
            'name'      => $_SESSION['admin_name'],
            'email'     => $_SESSION['admin_email'],
            'password'  => \Illuminate\Support\Facades\Hash::make($_SESSION['admin_pass']),
            'role'      => 'admin',
            'is_active' => true,
        ];
        if ($existing) {
            $existing->update($adminData);
            $log[] = '✔ Admin account updated';
        } else {
            $userModel->create($adminData);
            $log[] = '✔ Admin account created';
        }

        // 7. Storage link (skip if already linked)
        if (!file_exists(BASE_PATH . '/public/storage')) {
            ob_start();
            $kernel->call('storage:link');
            ob_get_clean();
            $log[] = '✔ Storage link created';
        } else {
            $log[] = '✔ Storage link already exists';
        }

        if (true) { // app key already embedded in config/local.php
            $log[] = '✔ Application key generated';
        }

        // 8. Clear caches
        $kernel->call('config:clear');
        $kernel->call('view:clear');
        $kernel->call('cache:clear');
        $log[] = '✔ Caches cleared';

        // 9. Write lock file
        file_put_contents(INSTALL_LOCK_FILE, date('Y-m-d H:i:s'));
        $log[] = '✔ Installation lock created';

        $_SESSION['install_log']     = $log;
        $_SESSION['install_success'] = true;
        $_SESSION['install_url']     = $_SESSION['app_url'];
        $_SESSION['install_email']   = $_SESSION['admin_email'];
        header('Location: install.php?step=5');
        exit;

    } catch (\Throwable $e) {
        $_SESSION['errors']      = ['Installation failed: ' . $e->getMessage()];
        $_SESSION['install_log'] = $log;
        header('Location: install.php?step=4&error=1');
        exit;
    }
}

function write_local_config(string $appKey): string {
    $dbConn = $_SESSION['db_connection'] ?? 'mysql';
    $dbDb   = ($dbConn === 'sqlite')
        ? BASE_PATH . '/database/database.sqlite'
        : ($_SESSION['db_name'] ?? '');

    // Build PHP array — no .env, no shell parsing, just plain PHP
    $config = [
        'app_name'  => $_SESSION['app_name']  ?? 'School ERP',
        'app_url'   => $_SESSION['app_url']   ?? 'http://localhost',
        'app_key'   => $appKey,
        'app_debug' => false,
        'app_env'   => 'production',
        'timezone'  => 'Asia/Kolkata',

        'db_connection' => $dbConn,
        'db_host'       => $_SESSION['db_host'] ?? 'localhost',
        'db_port'       => $_SESSION['db_port'] ?? '3306',
        'db_database'   => $dbDb,
        'db_username'   => $_SESSION['db_user'] ?? '',
        'db_password'   => $_SESSION['db_pass'] ?? '',

        'mail_mailer'     => 'log',
        'mail_host'       => 'smtp.gmail.com',
        'mail_port'       => 587,
        'mail_username'   => '',
        'mail_password'   => '',
        'mail_from'       => 'noreply@school.com',
        'mail_encryption' => 'tls',

        'payu_key'      => '',
        'payu_salt'     => '',
        'payu_base_url' => 'https://secure.payu.in/_payment',
    ];

    $php  = "<?php\n\n";
    $php .= "// School ERP — Local Configuration\n";
    $php .= "// Generated by web installer on " . date('Y-m-d H:i:s') . "\n";
    $php .= "// Edit this file or use Admin → Settings to change SMTP / PayU config.\n\n";
    $php .= "return [\n";
    foreach ($config as $k => $v) {
        $php .= "    '{$k}' => " . var_export($v, true) . ",\n";
    }
    $php .= "];\n";

    file_put_contents(BASE_PATH . '/config/local.php', $php);
    return '✔ config/local.php written (no .env needed)';
}

// ─── Requirements Check ───────────────────────────────────────────────────────
function check_requirements(): array {
    $checks = [];

    // PHP Version
    $checks[] = [
        'label'  => 'PHP Version (8.2+)',
        'status' => version_compare(PHP_VERSION, '8.2.0', '>='),
        'value'  => PHP_VERSION,
    ];

    // Required extensions
    foreach (['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo'] as $ext) {
        $checks[] = [
            'label'  => "Extension: {$ext}",
            'status' => extension_loaded($ext),
            'value'  => extension_loaded($ext) ? 'Loaded' : 'MISSING',
        ];
    }

    // At least one DB driver
    $hasMysql  = extension_loaded('pdo_mysql');
    $hasSqlite = extension_loaded('pdo_sqlite');
    $checks[] = [
        'label'  => 'PDO MySQL or SQLite',
        'status' => $hasMysql || $hasSqlite,
        'value'  => ($hasMysql ? 'MySQL ✔' : '') . ($hasMysql && $hasSqlite ? ', ' : '') . ($hasSqlite ? 'SQLite ✔' : ''),
    ];

    // Writable directories
    foreach ([
        BASE_PATH . '/storage'           => 'storage/',
        BASE_PATH . '/bootstrap/cache'   => 'bootstrap/cache/',
        BASE_PATH . '/database'          => 'database/',
        BASE_PATH . '/.env'              => '.env file (writable)',
    ] as $path => $label) {
        if ($path === BASE_PATH . '/.env') {
            $writable = !file_exists($path) || is_writable($path);
        } else {
            $writable = is_writable($path);
        }
        $checks[] = [
            'label'  => "Writable: {$label}",
            'status' => $writable,
            'value'  => $writable ? 'Writable' : 'NOT WRITABLE',
        ];
    }

    // Vendor directory
    $checks[] = [
        'label'  => 'Composer vendor/ folder',
        'status' => is_dir(BASE_PATH . '/vendor'),
        'value'  => is_dir(BASE_PATH . '/vendor') ? 'Found' : 'MISSING - upload vendor/ folder',
    ];

    return $checks;
}

$allPassed = true;
if ($step === 1) {
    $requirements = check_requirements();
    foreach ($requirements as $r) {
        if (!$r['status']) { $allPassed = false; break; }
    }
}

$sessionErrors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>School ERP Installer</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  body { background: #f1f5f9; font-family: 'Segoe UI', system-ui, sans-serif; }
  .installer-wrap { max-width: 740px; margin: 40px auto; padding: 0 15px 60px; }
  .installer-header { background: linear-gradient(135deg,#4f46e5,#06b6d4); color:#fff; border-radius: 16px 16px 0 0; padding: 32px; text-align:center; }
  .installer-header h1 { font-size:1.8rem; font-weight:800; margin:0; }
  .installer-header p { margin:4px 0 0; opacity:.85; }
  .installer-card { background:#fff; border-radius: 0 0 16px 16px; box-shadow: 0 4px 24px rgba(0,0,0,.1); padding: 36px; }
  .step-nav { display:flex; justify-content:center; gap:8px; margin-bottom:28px; }
  .step-dot { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.85rem; background:#e2e8f0; color:#64748b; }
  .step-dot.active { background:#4f46e5; color:#fff; }
  .step-dot.done   { background:#10b981; color:#fff; }
  .step-line { width:40px; height:3px; background:#e2e8f0; align-self:center; }
  .step-line.done { background:#10b981; }
  .req-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #f1f5f9; font-size:.9rem; }
  .badge-ok   { background:#dcfce7; color:#166534; padding:2px 10px; border-radius:20px; font-size:.8rem; }
  .badge-fail { background:#fee2e2; color:#991b1b; padding:2px 10px; border-radius:20px; font-size:.8rem; }
  .log-item { font-size:.88rem; padding:4px 0; border-bottom:1px solid #f8fafc; }
  .form-label { font-weight:600; font-size:.88rem; color:#374151; }
  .section-title { font-weight:700; font-size:1rem; color:#1e293b; margin:20px 0 12px; padding-bottom:6px; border-bottom:2px solid #e2e8f0; }
</style>
</head>
<body>
<div class="installer-wrap">

  <!-- Header -->
  <div class="installer-header">
    <h1><i class="bi bi-mortarboard-fill me-2"></i>School ERP Installer</h1>
    <p>Version <?= INSTALLER_VERSION ?> &nbsp;|&nbsp; Laravel 11</p>
  </div>

  <!-- Step Navigation -->
  <div class="installer-card">
    <div class="step-nav">
      <?php
        $steps = ['Requirements','Database','Configuration','Install','Complete'];
        foreach ($steps as $i => $label):
          $num = $i + 1;
          $cls = $num < $step ? 'done' : ($num === $step ? 'active' : '');
          $icon = $num < $step ? '<i class="bi bi-check-lg"></i>' : $num;
          if ($i > 0): ?><div class="step-line <?= $num <= $step ? 'done' : '' ?>"></div><?php endif;
      ?>
      <div style="text-align:center">
        <div class="step-dot <?= $cls ?>"><?= $icon ?></div>
        <div style="font-size:.7rem;margin-top:4px;color:#64748b;"><?= $label ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Error Messages -->
    <?php if ($sessionErrors): ?>
    <div class="alert alert-danger">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      <?= implode('<br>', array_map('htmlspecialchars', $sessionErrors)) ?>
    </div>
    <?php endif; ?>

    <?php
    // ─────────────────────────────────────────────────────────────────────────
    // STEP 1 — Requirements
    // ─────────────────────────────────────────────────────────────────────────
    if ($step === 1):
    ?>
    <h4 class="fw-bold mb-1">Step 1: Requirements Check</h4>
    <p class="text-muted small mb-4">Ensure your server meets all requirements before proceeding.</p>

    <?php foreach ($requirements as $r): ?>
    <div class="req-row">
      <span><?= htmlspecialchars($r['label']) ?></span>
      <span class="<?= $r['status'] ? 'badge-ok' : 'badge-fail' ?>">
        <?= htmlspecialchars($r['value']) ?>
      </span>
    </div>
    <?php endforeach; ?>

    <div class="mt-4">
    <?php if ($allPassed): ?>
      <a href="install.php?step=2" class="btn btn-primary px-4">
        <i class="bi bi-arrow-right me-1"></i>Continue to Database Setup
      </a>
    <?php else: ?>
      <div class="alert alert-warning mt-3">
        <i class="bi bi-exclamation-circle me-2"></i>
        Fix the failing requirements above, then
        <a href="install.php?step=1">refresh this page</a>.
      </div>
    <?php endif; ?>
    </div>

    <div class="alert alert-info mt-4 small">
      <strong>cPanel Tip:</strong> If extensions are missing, contact your hosting provider or enable them via
      <em>cPanel → PHP Selector → Extensions</em>.
    </div>

    <?php
    // ─────────────────────────────────────────────────────────────────────────
    // STEP 2 — Database
    // ─────────────────────────────────────────────────────────────────────────
    elseif ($step === 2):
    $hasMysql  = extension_loaded('pdo_mysql');
    $hasSqlite = extension_loaded('pdo_sqlite');
    ?>
    <h4 class="fw-bold mb-1">Step 2: Database Setup</h4>
    <p class="text-muted small mb-4">Choose your database type and enter connection details.</p>

    <form method="POST" action="install.php?step=2">
      <div class="mb-3">
        <label class="form-label">Database Type</label>
        <select name="db_connection" id="dbType" class="form-select" onchange="toggleDbFields()">
          <?php if ($hasMysql): ?><option value="mysql" selected>MySQL / MariaDB (recommended for cPanel)</option><?php endif; ?>
          <?php if ($hasSqlite): ?><option value="sqlite" <?= !$hasMysql ? 'selected' : '' ?>>SQLite (file-based, no setup needed)</option><?php endif; ?>
        </select>
      </div>

      <div id="mysqlFields">
        <div class="section-title">MySQL Connection Details</div>
        <div class="alert alert-info small">
          <strong>Where to find these:</strong> cPanel → MySQL Databases → your database details.<br>
          Host is usually <code>localhost</code> or <code>127.0.0.1</code> on shared hosting.
        </div>
        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label">Database Host</label>
            <input type="text" name="db_host" class="form-control" value="localhost" placeholder="localhost">
          </div>
          <div class="col-md-4">
            <label class="form-label">Port</label>
            <input type="text" name="db_port" class="form-control" value="3306">
          </div>
          <div class="col-md-12">
            <label class="form-label">Database Name <span class="text-danger">*</span></label>
            <input type="text" name="db_name" class="form-control" placeholder="e.g. username_schoolerp">
          </div>
          <div class="col-md-6">
            <label class="form-label">Database Username <span class="text-danger">*</span></label>
            <input type="text" name="db_user" class="form-control" placeholder="e.g. username_dbuser">
          </div>
          <div class="col-md-6">
            <label class="form-label">Database Password</label>
            <input type="password" name="db_pass" class="form-control" placeholder="Database password">
          </div>
        </div>
      </div>

      <div id="sqliteInfo" style="display:none">
        <div class="alert alert-success">
          <i class="bi bi-database-check me-2"></i>
          SQLite requires no configuration. A database file will be created automatically at
          <code>database/database.sqlite</code>.
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <a href="install.php?step=1" class="btn btn-outline-secondary">Back</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bi bi-plug me-1"></i>Test Connection &amp; Continue
        </button>
      </div>
    </form>

    <script>
    function toggleDbFields() {
      var t = document.getElementById('dbType').value;
      document.getElementById('mysqlFields').style.display = t === 'mysql' ? '' : 'none';
      document.getElementById('sqliteInfo').style.display  = t === 'sqlite' ? '' : 'none';
    }
    <?php if (!$hasMysql && $hasSqlite): ?>toggleDbFields();<?php endif; ?>
    </script>

    <?php
    // ─────────────────────────────────────────────────────────────────────────
    // STEP 3 — App Configuration
    // ─────────────────────────────────────────────────────────────────────────
    elseif ($step === 3):
    $guessedUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
                  . '://' . ($_SERVER['HTTP_HOST'] ?? 'yourdomain.com');
    ?>
    <h4 class="fw-bold mb-1">Step 3: Application Configuration</h4>
    <p class="text-muted small mb-4">Set your school name, website URL, and admin account.</p>

    <form method="POST" action="install.php?step=3">
      <div class="section-title">School / App Details</div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">School / App Name <span class="text-danger">*</span></label>
          <input type="text" name="app_name" class="form-control" value="<?= htmlspecialchars($_SESSION['app_name'] ?? 'School ERP') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Website URL <span class="text-danger">*</span></label>
          <input type="text" name="app_url" class="form-control"
            value="<?= htmlspecialchars($_SESSION['app_url'] ?? $guessedUrl) ?>"
            placeholder="https://yourdomain.com" required>
          <small class="text-muted">No trailing slash. Must match your domain exactly.</small>
        </div>
      </div>

      <div class="section-title mt-4">Admin Account</div>
      <div class="alert alert-warning small">
        <i class="bi bi-shield-lock me-1"></i>
        This creates your administrator login. Save these credentials securely.
      </div>
      <div class="row g-3">
        <div class="col-md-12">
          <label class="form-label">Admin Full Name</label>
          <input type="text" name="admin_name" class="form-control" value="<?= htmlspecialchars($_SESSION['admin_name'] ?? 'System Administrator') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Admin Email <span class="text-danger">*</span></label>
          <input type="email" name="admin_email" class="form-control" value="<?= htmlspecialchars($_SESSION['admin_email'] ?? '') ?>" placeholder="admin@yourschool.com" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Admin Password <span class="text-danger">*</span></label>
          <input type="password" name="admin_pass" class="form-control" placeholder="Min. 6 characters" required minlength="6">
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <a href="install.php?step=2" class="btn btn-outline-secondary">Back</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bi bi-arrow-right me-1"></i>Continue to Install
        </button>
      </div>
    </form>

    <?php
    // ─────────────────────────────────────────────────────────────────────────
    // STEP 4 — Install
    // ─────────────────────────────────────────────────────────────────────────
    elseif ($step === 4):
    ?>
    <h4 class="fw-bold mb-1">Step 4: Run Installation</h4>
    <p class="text-muted small mb-3">Review your settings and click Install. This will:</p>
    <ul class="small text-muted mb-4">
      <li>Write the <code>.env</code> configuration file</li>
      <li>Run database migrations (creates all tables)</li>
      <li>Seed default data (classes 1–12, settings, fee categories)</li>
      <li>Create your admin account</li>
      <li>Generate a secure application key</li>
      <li>Create the storage symlink</li>
    </ul>

    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="p-3 bg-light rounded">
          <div class="fw-bold small text-muted mb-1">Database</div>
          <div class="fw-semibold"><?= htmlspecialchars(strtoupper($_SESSION['db_connection'] ?? 'mysql')) ?></div>
          <?php if (($_SESSION['db_connection'] ?? '') === 'mysql'): ?>
          <div class="small text-muted"><?= htmlspecialchars($_SESSION['db_name'] ?? '') ?> @ <?= htmlspecialchars($_SESSION['db_host'] ?? '') ?></div>
          <?php else: ?>
          <div class="small text-muted">database/database.sqlite</div>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-3 bg-light rounded">
          <div class="fw-bold small text-muted mb-1">Application</div>
          <div class="fw-semibold"><?= htmlspecialchars($_SESSION['app_name'] ?? '') ?></div>
          <div class="small text-muted"><?= htmlspecialchars($_SESSION['app_url'] ?? '') ?></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="p-3 bg-light rounded">
          <div class="fw-bold small text-muted mb-1">Admin Account</div>
          <div class="fw-semibold"><?= htmlspecialchars($_SESSION['admin_name'] ?? '') ?></div>
          <div class="small text-muted"><?= htmlspecialchars($_SESSION['admin_email'] ?? '') ?></div>
        </div>
      </div>
    </div>

    <?php if (isset($_SESSION['install_log'])): ?>
    <div class="mb-3">
      <?php foreach ($_SESSION['install_log'] as $line): ?>
      <div class="log-item text-muted"><?= htmlspecialchars($line) ?></div>
      <?php endforeach; unset($_SESSION['install_log']); ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="install.php?step=4" id="installForm">
      <div class="d-flex gap-2">
        <a href="install.php?step=3" class="btn btn-outline-secondary">Back</a>
        <button type="submit" class="btn btn-success px-5 fw-bold" id="installBtn">
          <i class="bi bi-rocket-takeoff me-2"></i>Install Now
        </button>
      </div>
    </form>

    <script>
    document.getElementById('installForm').addEventListener('submit', function() {
      var btn = document.getElementById('installBtn');
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Installing... please wait';
    });
    </script>

    <?php
    // ─────────────────────────────────────────────────────────────────────────
    // STEP 5 — Complete
    // ─────────────────────────────────────────────────────────────────────────
    elseif ($step === 5):
    $siteUrl    = $_SESSION['install_url']   ?? '/';
    $adminEmail = $_SESSION['install_email'] ?? '';
    $log        = $_SESSION['install_log']   ?? [];
    unset($_SESSION['install_log'], $_SESSION['install_url'], $_SESSION['install_email']);
    ?>

    <div class="text-center mb-4">
      <div style="font-size:4rem;color:#10b981;"><i class="bi bi-check-circle-fill"></i></div>
      <h3 class="fw-bold mt-2">Installation Complete!</h3>
      <p class="text-muted">School ERP has been successfully installed.</p>
    </div>

    <?php if ($log): ?>
    <div class="mb-4 p-3 bg-light rounded">
      <?php foreach ($log as $line): ?>
      <div class="log-item"><?= htmlspecialchars($line) ?></div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="alert alert-warning">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      <strong>Security:</strong> Delete <code>install.php</code> from your server immediately via
      cPanel File Manager.
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="p-3 border rounded">
          <div class="fw-bold mb-1">Admin Login</div>
          <div class="small text-muted">Email: <?= htmlspecialchars($adminEmail) ?></div>
          <div class="small text-muted">URL: <?= htmlspecialchars($siteUrl) ?>/admin/dashboard</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-3 border rounded">
          <div class="fw-bold mb-1">Next Steps</div>
          <ul class="small text-muted mb-0 ps-3">
            <li>Delete <code>install.php</code></li>
            <li>Configure SMTP in Admin → Settings</li>
            <li>Set up PayU in Admin → Settings</li>
            <li>Customize school info &amp; logo</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="d-flex gap-3 justify-content-center">
      <a href="<?= htmlspecialchars($siteUrl) ?>/login" class="btn btn-primary px-4">
        <i class="bi bi-box-arrow-in-right me-1"></i>Go to Login
      </a>
      <a href="<?= htmlspecialchars($siteUrl) ?>/admin/dashboard" class="btn btn-outline-primary px-4">
        <i class="bi bi-speedometer2 me-1"></i>Admin Dashboard
      </a>
    </div>

    <?php endif; ?>

  </div><!-- /installer-card -->
</div><!-- /installer-wrap -->
</body>
</html>
<?php
function render_message(string $title, string $body, string $type = 'info'): string {
    $icon  = $type === 'success' ? 'check-circle-fill' : 'info-circle-fill';
    $color = $type === 'success' ? '#10b981' : '#4f46e5';
    return "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>{$title}</title>
      <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
      </head><body class='bg-light'>
      <div class='container' style='max-width:500px;margin:80px auto;text-align:center'>
        <i class='bi bi-{$icon}' style='font-size:4rem;color:{$color}'></i>
        <h3 class='mt-3 fw-bold'>{$title}</h3>
        <p>{$body}</p>
      </div></body></html>";
}
