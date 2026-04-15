<?php

/**
 * School ERP — Local Configuration
 * ----------------------------------
 * Copy this file to config/local.php and fill in your values.
 * The web installer (public/install.php) creates this file automatically.
 *
 * config/local.php is gitignored — it is never committed to version control.
 */

return [

    // ── Application ────────────────────────────────────────────────────────
    'app_name'  => 'School ERP',
    'app_url'   => 'https://yourdomain.com',
    'app_key'   => '',          // Generated automatically by the installer
    'app_debug' => false,
    'app_env'   => 'production',
    'timezone'  => 'Asia/Kolkata',

    // ── Database ───────────────────────────────────────────────────────────
    'db_connection' => 'mysql',  // 'mysql' or 'sqlite'
    'db_host'       => 'localhost',
    'db_port'       => '3306',
    'db_database'   => '',
    'db_username'   => '',
    'db_password'   => '',

    // ── Mail / SMTP ────────────────────────────────────────────────────────
    // Leave mail_mailer as 'log' until you configure SMTP in Admin → Settings
    'mail_mailer'     => 'log',
    'mail_host'       => 'smtp.gmail.com',
    'mail_port'       => 587,
    'mail_username'   => '',
    'mail_password'   => '',
    'mail_from'       => 'noreply@yourdomain.com',
    'mail_encryption' => 'tls',

    // ── PayU Payment Gateway ───────────────────────────────────────────────
    'payu_key'      => '',
    'payu_salt'     => '',
    'payu_base_url' => 'https://secure.payu.in/_payment',

];
