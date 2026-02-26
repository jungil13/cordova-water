<?php
/**
 * Facebook OAuth callback
 */
require_once __DIR__ . '/../includes/auth.php';

if (!FB_APP_ID || !FB_APP_SECRET) {
    header('Location: ' . BASE_URL . '/login.php?error=fb_not_configured');
    exit;
}

$code = $_GET['code'] ?? null;
if (!$code) {
    header('Location: ' . BASE_URL . '/login.php?error=no_code');
    exit;
}

$redirectUri = rtrim(BASE_URL, '/') . '/auth/facebook-callback.php';

// Exchange code for token
$tokenUrl = 'https://graph.facebook.com/v18.0/oauth/access_token?' . http_build_query([
    'client_id' => FB_APP_ID,
    'client_secret' => FB_APP_SECRET,
    'redirect_uri' => $redirectUri,
    'code' => $code,
]);

$ch = curl_init($tokenUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
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
$userUrl = 'https://graph.facebook.com/me?fields=id,name,email,picture&access_token=' . urlencode($tokenData['access_token']);

$ch = curl_init($userUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
]);

$userJson = curl_exec($ch);
curl_close($ch);

$fbUser = json_decode($userJson, true);
if (empty($fbUser['id'])) {
    header('Location: ' . BASE_URL . '/login.php?error=no_user');
    exit;
}

$email = $fbUser['email'] ?? ($fbUser['id'] . '@facebook.user');
$picture = $fbUser['picture']['data']['url'] ?? null;

$userData = [
    'email' => $email,
    'name' => $fbUser['name'] ?? 'Facebook User',
    'picture' => $picture,
    'provider' => 'facebook',
    'provider_id' => (string)$fbUser['id'],
];

loginUser($userData);

$redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . '/dashboard/';
unset($_SESSION['redirect_after_login']);
header('Location: ' . $redirect);
exit;
