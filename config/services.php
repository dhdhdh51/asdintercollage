<?php

// Load local config (written by the web installer — no .env needed)
$_lc = file_exists(__DIR__ . '/local.php') ? require __DIR__ . '/local.php' : [];

return [

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // PayU Payment Gateway — configured via Admin → Settings or config/local.php
    'payu' => [
        'key'      => $_lc['payu_key']      ?? env('PAYU_MERCHANT_KEY', ''),
        'salt'     => $_lc['payu_salt']     ?? env('PAYU_MERCHANT_SALT', ''),
        'base_url' => $_lc['payu_base_url'] ?? env('PAYU_BASE_URL', 'https://secure.payu.in/_payment'),
    ],

];
