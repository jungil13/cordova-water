<?php
/**
 * Database connection - PDO (MySQL / XAMPP)
 */
require_once __DIR__ . '/config.php';

$dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';

try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    // Aiven MySQL typically requires SSL; this relaxes certificate verification
    // so it works without downloading CA certs locally or on Vercel.
    if (strpos(DB_HOST, 'aivencloud.com') !== false) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    if (php_sapi_name() === 'cli') {
        die("Database connection failed: " . $e->getMessage() . "\n");
    }
    http_response_code(500);
    die('Database connection failed. Please check configuration.');
}
