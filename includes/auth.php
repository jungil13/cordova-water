<?php
// Start session at the absolute top to prevent "headers already sent"
if (session_status() === PHP_SESSION_NONE) {
    // Default session lifetime if config not loaded yet
    session_set_cookie_params(['lifetime' => 604800]);
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
        return $stmt->fetch();
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
    if (!isLoggedIn()) {
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
    return $user && $user['role'] === 'admin';
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
