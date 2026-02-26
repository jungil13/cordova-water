<?php
/**
 * Notifications Helper Functions
 */
require_once __DIR__ . '/db.php';

/**
 * Add a new notification for a user
 */
function addNotification($userId, $message, $type = 'info')
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, type) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $message, $type]);
    }
    catch (PDOException $e) {
        return false;
    }
}

/**
 * Get unread notifications for a user
 */
function getUnreadNotifications($userId, $limit = 5)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM notifications 
            WHERE user_id = ? AND is_read = FALSE 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    catch (PDOException $e) {
        return [];
    }
}

/**
 * Mark notification as read
 */
function markNotificationAsRead($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ?");
        return $stmt->execute([$id]);
    }
    catch (PDOException $e) {
        return false;
    }
}
