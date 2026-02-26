<?php
/**
 * One-time setup script - Run this to create database and tables
 * Access: php setup.php (CLI) or visit /setup.php once
 * DELETE THIS FILE after setup for security!
 */

// Only allow from CLI or localhost
if (php_sapi_name() !== 'cli' && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
    die('Setup can only be run from CLI or localhost.');
}

require_once __DIR__ . '/includes/config.php';

echo "Cordova Water System - Database Setup\n";
echo "=====================================\n\n";

try {
    // Connect without database first
    $pdo = new PDO('mysql:host=' . DB_HOST, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $pdo->exec("USE " . DB_NAME);
    echo "✓ Database created/selected\n";

    // Run schema
    $schema = file_get_contents(__DIR__ . '/sql/schema.sql');
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    
    foreach ($statements as $stmt) {
        if (empty($stmt) || strpos($stmt, '--') === 0) continue;
        if (preg_match('/^(CREATE DATABASE|USE)\s/i', $stmt)) continue;
        $pdo->exec($stmt);
    }
    echo "✓ Tables created\n";

    // Create admin user with password: admin123
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (email, name, role, provider, password_hash) VALUES ('admin@cordovawater.com', 'Administrator', 'admin', 'local', ?) ON DUPLICATE KEY UPDATE password_hash = ?");
    $stmt->execute([$hash, $hash]);
    echo "✓ Admin user: admin@cordovawater.com / admin123 (CHANGE THIS!)\n";

    echo "\nSetup complete! Delete setup.php for security.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
