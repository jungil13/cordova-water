<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';

$user = currentUser();
if (!$user) {
    die("Please log in first.");
}

$data = [
    'userId' => $user['id'],
    'title' => 'Test Notification',
    'message' => 'This is a test notification sent at ' . date('Y-m-d H:i:s')
];

$ch = curl_init(SOCKET_URL . '/notify');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h1>Notification Test</h1>";
echo "<p>Sent to User ID: " . $user['id'] . "</p>";
echo "<p>Socket Server Response (HTTP $httpCode): " . $response . "</p>";
echo "<p><a href='index.php'>Go back to Home</a></p>";
?>
