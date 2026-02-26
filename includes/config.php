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
            $key = trim($key);
            $val = trim($val);
            if (!empty($key)) {
                putenv("$key=$val");
                $_ENV[$key] = $val;
            }
        }
    }
}

/**
 * Get env variable
 */
function env($key, $default = null)
{
    $val = getenv($key);
    if ($val === false) {
        return $_ENV[$key] ?? $val ?: $default;
    }
    return $val;
}

// App Settings
define('BASE_URL', env('BASE_URL', 'http://localhost:8000'));

// Database
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_NAME', env('DB_NAME', 'cordova_water'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));

// Google OAuth
define('GOOGLE_CLIENT_ID', env('GOOGLE_CLIENT_ID', ''));
define('GOOGLE_CLIENT_SECRET', env('GOOGLE_CLIENT_SECRET', ''));

// Facebook OAuth
define('FB_APP_ID', env('FB_APP_ID', ''));
define('FB_APP_SECRET', env('FB_APP_SECRET', ''));

// Session
define('SESSION_LIFETIME', 86400 * 7); // 7 days
