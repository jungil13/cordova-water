<?php
/**
 * Configuration - Cordova Water System Inc.
 * Copy .env.example to .env and fill in your values
 */

// Load .env if exists (for local dev)
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($val, " \t\n\r\0\x0B\"'");
        }
    }
}

// Helper to get env var
function env($key, $default = null) {
    $val = $_ENV[$key] ?? getenv($key);
    return $val !== false ? $val : $default;
}

// Base URL (no trailing slash)
define('BASE_URL', env('BASE_URL', 'http://localhost:8000'));

// Database
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_NAME', env('DB_NAME', 'cordova_water'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));

// Google OAuth - Get from https://console.cloud.google.com/
define('GOOGLE_CLIENT_ID', env('GOOGLE_CLIENT_ID', ''));
define('GOOGLE_CLIENT_SECRET', env('GOOGLE_CLIENT_SECRET', ''));

// Facebook OAuth - Get from https://developers.facebook.com/
define('FB_APP_ID', env('FB_APP_ID', ''));
define('FB_APP_SECRET', env('FB_APP_SECRET', ''));

// Session
define('SESSION_LIFETIME', 86400 * 7); // 7 days
