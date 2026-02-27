<?php
/**
 * One-time setup script - Run this to create MySQL database and tables
 * Access: php setup.php (CLI) or visit /setup.php once
 * DELETE THIS FILE after setup for security!
 */

// Only allow from CLI or localhost
if (php_sapi_name() !== 'cli' && ($_SERVER['REMOTE_ADDR'] ?? '') !== '127.0.0.1') {
    die('Setup can only be run from CLI or localhost.');
}

require_once __DIR__ . '/includes/config.php';

echo "Cordova Water System - MySQL Database Setup\n";
echo "==========================================\n\n";

try {
    // Connect to MySQL server without specifying a database
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Run MySQL schema (creates database + tables)
    $schemaFile = __DIR__ . '/sql/schema.sql';
    if (!file_exists($schemaFile)) {
        throw new RuntimeException('Schema file not found: ' . $schemaFile);
    }

    $schema = file_get_contents($schemaFile);
    $statements = array_filter(array_map('trim', explode(';', $schema)));

    foreach ($statements as $stmt) {
        if ($stmt === '' || strpos($stmt, '--') === 0) {
            continue;
        }
        $pdo->exec($stmt);
    }

    echo "✓ Database and tables created/updated\n";

    // Create admin user with password: admin123 if not exists
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec('USE cordova_water');
    $stmt = $pdo->prepare("
        INSERT INTO users (email, name, role, provider, password_hash)
        VALUES ('admin@cordovawater.com', 'Administrator', 'admin', 'local', :hash)
        ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)
    ");
    $stmt->execute(['hash' => $hash]);
    echo "✓ Admin user: admin@cordovawater.com / admin123 (CHANGE THIS!)\n";

    echo "\nSetup complete! Delete setup.php for security.\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
