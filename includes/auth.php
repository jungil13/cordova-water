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
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user
 */
function currentUser() {
    global $pdo;
    if (!isLoggedIn()) return null;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Check if user is admin or staff
 */
function isAdmin() {
    $user = currentUser();
    return $user && in_array($user['role'], ['admin', 'staff']);
}

/**
 * Require login - redirect if not logged in
 */
function requireLogin($redirectTo = null) {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $redirectTo ?? $_SERVER['REQUEST_URI'];
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

/**
 * Require admin - redirect if not admin/staff
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

/**
 * Login user (create/update user from OAuth)
 */
function loginUser($userData) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT * FROM users WHERE (provider = ? AND provider_id = ?) OR email = ?
    ");
    $stmt->execute([
        $userData['provider'],
        $userData['provider_id'] ?? '',
        $userData['email']
    ]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $pdo->prepare("
            UPDATE users SET name=?, picture=?, provider=?, provider_id=?, updated_at=NOW()
            WHERE id=?
        ");
        $stmt->execute([
            $userData['name'],
            $userData['picture'] ?? null,
            $userData['provider'],
            $userData['provider_id'] ?? null,
            $user['id']
        ]);
        $userId = $user['id'];
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO users (email, name, picture, provider, provider_id) VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $userData['email'],
            $userData['name'],
            $userData['picture'] ?? null,
            $userData['provider'],
            $userData['provider_id'] ?? null
        ]);
        $userId = $pdo->lastInsertId();
    }

    $_SESSION['user_id'] = $userId;
    return $userId;
}

/**
 * Logout
 */
function logout() {
    session_destroy();
    session_start();
}
