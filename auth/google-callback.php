<?php
/**
 * Google OAuth callback
 */
require_once __DIR__ . '/../includes/auth.php';

if (!GOOGLE_CLIENT_ID || !GOOGLE_CLIENT_SECRET) {
    header('Location: ' . BASE_URL . '/login.php?error=google_not_configured');
    exit;
}

$code = $_GET['code'] ?? null;
if (!$code) {
    header('Location: ' . BASE_URL . '/login.php?error=no_code');
    exit;
}

$redirectUri = rtrim(BASE_URL, '/') . '/auth/google-callback.php';

// Exchange code for token
$post = [
    'code' => $code,
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => $redirectUri,
    'grant_type' => 'authorization_code',
];

$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($post),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$tokenData = json_decode($response, true);
if ($httpCode !== 200 || empty($tokenData['access_token'])) {
    header('Location: ' . BASE_URL . '/login.php?error=token_failed');
    exit;
}

// Get user info
$ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $tokenData['access_token']],
]);

$userJson = curl_exec($ch);
curl_close($ch);

$googleUser = json_decode($userJson, true);
if (empty($googleUser['email'])) {
    header('Location: ' . BASE_URL . '/login.php?error=no_email');
    exit;
}

$userData = [
    'email' => $googleUser['email'],
    'name' => $googleUser['name'] ?? $googleUser['email'],
    'picture' => $googleUser['picture'] ?? null,
    'provider' => 'google',
    'provider_id' => (string)$googleUser['id'],
];

loginUser($userData);

$redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . '/dashboard/';
unset($_SESSION['redirect_after_login']);
header('Location: ' . $redirect);
exit;
