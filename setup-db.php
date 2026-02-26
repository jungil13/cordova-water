<?php
/**
 * One-time Database Setup Script
 * This script will run your schema.sql on your Aiven database.
 */
require_once __DIR__ . '/includes/db.php';

echo "<h2>Cordova Water System - Database Setup</h2>";

try {
    $sql = file_get_contents(__DIR__ . '/sql/schema.sql');

    // Remove the CREATE DATABASE and USE lines as Aiven handles the DB name
    $sql = preg_replace('/CREATE DATABASE.*?;/is', '', $sql);
    $sql = preg_replace('/USE .*?;/is', '', $sql);

    echo "Connecting to: " . DB_HOST . "...<br>";

    // Execute the SQL
    $pdo->exec($sql);

    echo "<div style='color: green; font-weight: bold;'>SUCCESS! Your database tables have been created on Aiven.</div>";
    echo "<p>You can now delete this file (<code>setup-db.php</code>) for security.</p>";
    echo "<a href='index.php'>Go to Home Page</a>";

}
catch (Exception $e) {
    echo "<div style='color: red; font-weight: bold;'>ERROR: " . $e->getMessage() . "</div>";
    echo "<p>Make sure your .env file has the correct Aiven credentials.</p>";
}
