<?php
/**
 * School ERP — Auto Setup Script
 * --------------------------------
 * Upload the project files to cPanel, then visit this URL first:
 *   https://yourdomain.com/setup.php
 *
 * This script will:
 *  1. Download Composer (PHP package manager)
 *  2. Install all PHP dependencies (vendor/ folder)
 *  3. Redirect you to the web installer (install.php)
 *
 * DELETE this file after installation is complete.
 */

define('BASE_PATH', dirname(__DIR__));
set_time_limit(300); // 5 minutes max
ini_set('display_errors', 1);
error_reporting(E_ALL);

$step   = $_GET['action'] ?? 'check';
$errors = [];
$log    = [];

// ── Helpers ──────────────────────────────────────────────────────────────────
function exec_available(): bool {
    if (!function_exists('exec')) return false;
    $disabled = array_map('trim', explode(',', ini_get('disable_functions')));
    return !in_array('exec', $disabled, true);
}

function php_binary(): string {
    foreach ([PHP_BINARY, 'php', 'php8.2', 'php8.1', 'php8.0', '/usr/local/bin/php', '/usr/bin/php'] as $php) {
        if (@exec($php . ' -r "echo 1;" 2>/dev/null') === '1') return $php;
    }
    return PHP_BINARY;
}

function download_file(string $url, string $dest): bool {
    // Try curl extension first
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        $fp = fopen($dest, 'wb');
        curl_setopt_array($ch, [
            CURLOPT_FILE           => $fp,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT      => 'Mozilla/5.0',
        ]);
        curl_exec($ch);
        $ok = !curl_error($ch);
        curl_close($ch);
        fclose($fp);
        return $ok && filesize($dest) > 1000;
    }

    // Fallback: file_get_contents with context
    $ctx = stream_context_create(['http' => [
        'timeout'         => 120,
        'follow_location' => true,
        'user_agent'      => 'Mozilla/5.0',
    ], 'ssl' => ['verify_peer' => false]]);
    $data = @file_get_contents($url, false, $ctx);
    if ($data && strlen($data) > 1000) {
        file_put_contents($dest, $data);
        return true;
    }
    return false;
}

// ── Actions ───────────────────────────────────────────────────────────────────

if ($step === 'install_composer') {
    header('Content-Type: text/plain');
    echo "=== School ERP Setup ===\n\n";

    // Check vendor already exists
    if (is_dir(BASE_PATH . '/vendor') && file_exists(BASE_PATH . '/vendor/autoload.php')) {
        echo "[OK] vendor/ already exists — skipping Composer install.\n";
        echo "DONE";
        exit;
    }

    if (!exec_available()) {
        echo "[ERROR] PHP exec() is disabled on this server.\n";
        echo "Please ask your hosting provider to enable exec() or whitelist it.\n";
        echo "Alternatively, run: composer install --no-dev  from your local machine\n";
        echo "and upload the vendor/ folder via FTP.\n";
        exit;
    }

    $composerPhar = BASE_PATH . '/composer.phar';

    // Download composer.phar
    if (!file_exists($composerPhar)) {
        echo "[...] Downloading Composer...\n";
        flush();
        $ok = download_file('https://getcomposer.org/composer-stable.phar', $composerPhar);
        if (!$ok) {
            echo "[ERROR] Could not download Composer from getcomposer.org\n";
            echo "Your server may not have outbound internet access.\n";
            exit;
        }
        echo "[OK]  Composer downloaded.\n";
        flush();
    } else {
        echo "[OK]  Composer already downloaded.\n";
    }

    // Run composer install
    $php = php_binary();
    echo "[...] Running: composer install --no-dev --optimize-autoloader\n";
    echo "      This may take 2-4 minutes...\n\n";
    flush();

    $cmd    = "cd " . escapeshellarg(BASE_PATH) . " && " . escapeshellarg($php) . " composer.phar install --no-dev --optimize-autoloader --no-interaction 2>&1";
    $output = [];
    $code   = 0;
    exec($cmd, $output, $code);

    foreach ($output as $line) {
        echo $line . "\n";
        flush();
    }

    if ($code !== 0 || !file_exists(BASE_PATH . '/vendor/autoload.php')) {
        echo "\n[ERROR] Composer install failed (exit code: {$code})\n";
        exit;
    }

    // Clean up composer.phar
    @unlink($composerPhar);

    echo "\n[OK]  All packages installed successfully!\n";
    echo "DONE";
    exit;
}

// ── Check Status ──────────────────────────────────────────────────────────────
$checks = [
    'PHP 8.2+' => [
        'ok'  => version_compare(PHP_VERSION, '8.2.0', '>='),
        'val' => PHP_VERSION,
    ],
    'exec() function' => [
        'ok'  => exec_available(),
        'val' => exec_available() ? 'Available ✔' : 'Disabled ✗',
    ],
    'curl extension' => [
        'ok'  => extension_loaded('curl'),
        'val' => extension_loaded('curl') ? 'Loaded ✔' : 'Missing ✗',
    ],
    'vendor/ folder' => [
        'ok'  => is_dir(BASE_PATH . '/vendor'),
        'val' => is_dir(BASE_PATH . '/vendor') ? 'Found ✔' : 'Not yet installed',
    ],
    'storage/ writable' => [
        'ok'  => is_writable(BASE_PATH . '/storage'),
        'val' => is_writable(BASE_PATH . '/storage') ? 'Writable ✔' : 'Not writable ✗',
    ],
    'install.php exists' => [
        'ok'  => file_exists(__DIR__ . '/install.php'),
        'val' => file_exists(__DIR__ . '/install.php') ? 'Found ✔' : 'Missing ✗',
    ],
];

$vendorReady    = is_dir(BASE_PATH . '/vendor') && file_exists(BASE_PATH . '/vendor/autoload.php');
$canAutoInstall = exec_available() && extension_loaded('curl');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>School ERP — Auto Setup</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  body { background:#f1f5f9; font-family:'Segoe UI',system-ui,sans-serif; }
  .wrap { max-width:680px; margin:50px auto; padding:0 15px 60px; }
  .card { border:none; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,.08); }
  .card-header { background:linear-gradient(135deg,#4f46e5,#06b6d4); color:#fff; border-radius:16px 16px 0 0!important; padding:28px 32px; }
  .card-body { padding:32px; }
  .check-row { display:flex; justify-content:space-between; align-items:center; padding:9px 0; border-bottom:1px solid #f1f5f9; font-size:.9rem; }
  .badge-ok  { background:#dcfce7; color:#166534; padding:3px 12px; border-radius:20px; font-size:.8rem; }
  .badge-no  { background:#fee2e2; color:#991b1b; padding:3px 12px; border-radius:20px; font-size:.8rem; }
  .badge-warn{ background:#fef9c3; color:#854d0e; padding:3px 12px; border-radius:20px; font-size:.8rem; }
  pre { background:#0f172a; color:#e2e8f0; border-radius:10px; padding:16px; font-size:.82rem; max-height:360px; overflow-y:auto; }
</style>
</head>
<body>
<div class="wrap">
<div class="card">
  <div class="card-header">
    <h4 class="mb-0 fw-bold"><i class="bi bi-gear-wide-connected me-2"></i>School ERP — Auto Setup</h4>
    <div class="small opacity-85 mt-1">Step 1 of 2 — Installs PHP packages (vendor/)</div>
  </div>
  <div class="card-body">

    <h6 class="fw-bold mb-3">System Status</h6>
    <?php foreach ($checks as $label => $c): ?>
    <div class="check-row">
      <span><?= htmlspecialchars($label) ?></span>
      <span class="<?= $c['ok'] ? 'badge-ok' : 'badge-no' ?>"><?= htmlspecialchars($c['val']) ?></span>
    </div>
    <?php endforeach; ?>

    <div class="mt-4">
    <?php if ($vendorReady): ?>
      <div class="alert alert-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>PHP packages already installed!</strong><br>
        Proceed to the Web Installer to finish setup.
      </div>
      <a href="install.php" class="btn btn-success px-4 fw-bold">
        <i class="bi bi-arrow-right me-1"></i>Open Web Installer
      </a>

    <?php elseif ($canAutoInstall): ?>
      <div class="alert alert-info small">
        <i class="bi bi-info-circle me-2"></i>
        Click the button below. This will download Composer and install all PHP packages
        automatically on your server. It may take <strong>2–4 minutes</strong>.
        Do not close this page.
      </div>
      <button class="btn btn-primary px-4 fw-bold" id="installBtn" onclick="runInstall()">
        <i class="bi bi-download me-1"></i>Auto-Install PHP Packages
      </button>
      <pre id="logBox" style="display:none;margin-top:20px;"></pre>
      <div id="doneMsg" style="display:none;" class="mt-3"></div>

    <?php else: ?>
      <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>exec() is disabled</strong> on this server — automatic install not possible.<br><br>
        <strong>Option 1 (easiest):</strong> Ask your hosting provider to enable <code>exec()</code>.<br>
        <strong>Option 2:</strong> If you have PHP on your local computer, run:<br>
        <code>composer install --no-dev</code> in the project folder, then upload the
        <code>vendor/</code> folder via cPanel File Manager.
      </div>
    <?php endif; ?>
    </div>

    <div class="mt-4 pt-3 border-top small text-muted">
      <i class="bi bi-shield-lock me-1"></i>
      <strong>Security reminder:</strong> Delete <code>setup.php</code> and <code>install.php</code>
      from cPanel File Manager after your installation is complete.
    </div>
  </div>
</div>
</div>

<script>
function runInstall() {
  document.getElementById('installBtn').disabled = true;
  document.getElementById('installBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Installing... please wait';
  var logBox = document.getElementById('logBox');
  logBox.style.display = 'block';
  logBox.textContent = 'Starting installation...\n';

  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'setup.php?action=install_composer', true);
  xhr.onprogress = function() {
    logBox.textContent = xhr.responseText;
    logBox.scrollTop = logBox.scrollHeight;
  };
  xhr.onload = function() {
    logBox.textContent = xhr.responseText;
    logBox.scrollTop = logBox.scrollHeight;
    var doneMsg = document.getElementById('doneMsg');
    if (xhr.responseText.includes('DONE')) {
      doneMsg.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><strong>Installation complete!</strong> <a href="install.php" class="btn btn-success ms-3">Open Web Installer &rarr;</a></div>';
    } else {
      doneMsg.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>Installation failed. See output above.</div>';
      document.getElementById('installBtn').disabled = false;
      document.getElementById('installBtn').innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Retry';
    }
    doneMsg.style.display = 'block';
  };
  xhr.onerror = function() {
    document.getElementById('doneMsg').innerHTML = '<div class="alert alert-danger">Connection error. Please try again.</div>';
    document.getElementById('doneMsg').style.display = 'block';
  };
  xhr.send();
}
</script>
</body>
</html>
