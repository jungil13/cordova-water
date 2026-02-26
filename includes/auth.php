<?php
/**
 * Authentication - Session, OAuth helpers
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['lifetime' => SESSION_LIFETIME]);
    session_start();
}

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
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Check if user is admin or staff
 */
function isAdmin()
{
    $user = currentUser();
    return $user && in_array($user['role'], ['admin', 'staff']);
}

/**
 * Require login - redirect if not logged in
 */
function requireLogin($redirectTo = null)
{
    if (!isLoggedIn() || !currentUser()) {
        if (isLoggedIn())
            unset($_SESSION['user_id']); // Clear invalid session
        $_SESSION['redirect_after_login'] = $redirectTo ?? $_SERVER['REQUEST_URI'];
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

/**
 * Require admin - redirect if not admin/staff
 */
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

/**
 * Login user (Manual email/password)
 */
function loginUser($email, $password)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        return $user;
    }
    return false;
}

/**
 * Register user (Manual email/password)
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
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password_hash, provider, role) 
            VALUES (?, ?, ?, 'local', 'user')
        ");
        $stmt->execute([$name, $email, $passwordHash]);
        return ['success' => true, 'id' => $pdo->lastInsertId()];
    }
    catch (PDOException $e) {
        return ['error' => 'Registration failed: ' . $e->getMessage()];
    }
}

/**
 * Logout
 */
function logout()
{
    session_destroy();
    session_start();
}
