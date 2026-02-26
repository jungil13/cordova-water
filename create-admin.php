<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

// This script should be deleted after use or protected
// For now, let's make it easy for the user to create an admin

$email = 'admin@example.com'; // Change this to the user's email
$password = 'admin123'; // Change this to a secure password
$name = 'System Administrator';

if (isset($_GET['email']))
    $email = $_GET['email'];
if (isset($_GET['pass']))
    $password = $_GET['pass'];

echo "<h1>Admin Creation Tool</h1>";

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Promote to admin
        $stmt = $pdo->prepare("UPDATE users SET role = 'admin', password_hash = ? WHERE id = ?");
        $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
        echo "<p style='color:green'>Success: User <b>$email</b> has been promoted to ADMIN.</p>";
    }
    else {
        // Create new admin
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, provider) VALUES (?, ?, ?, 'admin', 'local')");
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
        echo "<p style='color:green'>Success: New admin account created for <b>$email</b>.</p>";
    }

    echo "<p>You can now <a href='login.php'>Login here</a>.</p>";
    echo "<p style='color:red'><b>IMPORTANT:</b> Delete this file (create-admin.php) after use for security.</p>";

}
catch (PDOException $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>
