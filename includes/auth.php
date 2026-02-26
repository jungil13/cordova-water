<?php
// Start session at the absolute top to prevent "headers already sent"
if (session_status() === PHP_SESSION_NONE) {
    // Default session lifetime (7 days)
    session_set_cookie_params(['lifetime' => 604800, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
}

/**
 * Authentication - Session, OAuth helpers
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Get current user
 */
function currentUser()
{
    global $pdo;
    if (!isLoggedIn())
        return null;

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        return $user ?: null; // Ensure null if fetch fails or returns false
    }
    catch (PDOException $e) {
        return null;
    }
}

/**
 * Require login to access page
 */
function requireLogin()
{
    if (!isLoggedIn() || currentUser() === null) {
        // Clear session if logged in but user record is missing
        if (isLoggedIn()) {
            $_SESSION = [];
        }
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

/**
 * Check if user is admin
 */
function isAdmin()
{
    $user = currentUser();
    return $user && ($user['role'] === 'admin' || $user['role'] === 'staff');
}

/**
 * Require admin role to access page
 */
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . '/dashboard/');
        exit;
    }
}

/**
 * Logout user
 */
function logout()
{
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

/**
 * Register a new user
 */
function registerUser($name, $email, $password)
{
    global $pdo;

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['error' => 'Email already registered.'];
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $passwordHash]);
        return ['success' => true];
    }
    catch (PDOException $e) {
        return ['error' => 'Registration failed. Please try again.'];
    }
}

/**
 * Log in a user
 */
function loginUser($email, $password)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            return $user;
        }
    }
    catch (PDOException $e) {
        return false;
    }

    return false;
}
