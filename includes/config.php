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
// App Settings
$default_base = 'http://localhost:8000';
$socket_base = 'http://localhost:3000';

$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? null;

if ($host) {
    // Determine Protocol
    $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
        (strpos($host, 'devtunnels.ms') !== false); // DevTunnels always use HTTPS

    $protocol = $isHttps ? 'https://' : 'http://';
    $default_base = $protocol . $host;

    // Auto-detect socket URL for DevTunnels or localhost port differences
    if (preg_match('/\-8000\.([a-z0-9\-]+\.devtunnels\.ms)$/', $host)) {
        $socket_host = str_replace('-8000.', '-3000.', $host);
        $socket_base = $protocol . $socket_host;
    }
    elseif (strpos($host, ':8000') !== false) {
        $socket_host = str_replace(':8000', ':3000', $host);
        $socket_base = $protocol . $socket_host;
    }
    else {
        $socket_base = $protocol . $host; // Fallback
    }
}

// Override with env if explicitly set, otherwise use dynamic detection
define('BASE_URL', getenv('BASE_URL') ?: ($_ENV['BASE_URL'] ?? $default_base));
define('SOCKET_URL', getenv('SOCKET_URL') ?: ($_ENV['SOCKET_URL'] ?? $socket_base));

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
