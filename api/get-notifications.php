<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/notifications.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user = currentUser();
$notifications = getUnreadNotifications($user['id']);

echo json_encode([
    'notifications' => $notifications,
    'count' => count($notifications)
]);
