<?php
/**
 * Configuration - Cordova Water System Inc.
 * Copy .env.example to .env and fill in your values
 */

// Load .env if exists (for local dev)
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($val, " \t\n\r\0\x0B\"'");
        }
    }
}

// Helper to get env var
function env($key, $default = null)
{
    $val = $_ENV[$key] ?? getenv($key);
    return $val !== false ? $val : $default;
}

// Base URL Detection (Handles Local & Vercel Proxy)
$proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
$detectedBase = $proto . "://" . $host;

// Use detected base if ENV is localhost or empty
$envBase = env('BASE_URL');
if (!$envBase || strpos($envBase, 'localhost') !== false) {
    define('BASE_URL', $detectedBase);
}
else {
    define('BASE_URL', $envBase);
}

// Database
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_PORT', env('DB_PORT', '3306'));
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
