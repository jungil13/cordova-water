<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/dashboard/');
    exit;
}

// Handle email/password login (for admin/staff)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password_hash IS NOT NULL");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . '/dashboard/';
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirect);
            exit;
        }
    }
    $loginError = 'Invalid email or password.';
}

$error = $_GET['error'] ?? null;
$errorMsg = null;
if ($error) {
    $errorMsg = in_array($error, ['google_not_configured', 'fb_not_configured']) ? 'Social login is not configured.' :
        (in_array($error, ['no_code', 'token_failed']) ? 'Authentication failed. Please try again.' :
        (in_array($error, ['no_email', 'no_user']) ? 'Could not get your profile.' : 'Something went wrong. Please try again.'));
}

$googleUrl = '';
if (GOOGLE_CLIENT_ID) {
    $redirectUri = rtrim(BASE_URL, '/') . '/auth/google-callback.php';
    $googleUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
        'client_id' => GOOGLE_CLIENT_ID,
        'redirect_uri' => $redirectUri,
        'response_type' => 'code',
        'scope' => 'email profile',
        'access_type' => 'online',
    ]);
}

$facebookUrl = '';
if (FB_APP_ID) {
    $redirectUri = rtrim(BASE_URL, '/') . '/auth/facebook-callback.php';
    $facebookUrl = 'https://www.facebook.com/v18.0/dialog/oauth?' . http_build_query([
        'client_id' => FB_APP_ID,
        'redirect_uri' => $redirectUri,
        'response_type' => 'code',
        'scope' => 'email,public_profile',
    ]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cordova Water System Inc.</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68',dark:'#083A4D'}}}}}</script>
</head>
<body class="font-sans antialiased bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <a href="<?= BASE_URL ?>/" class="inline-block text-2xl font-bold text-primary mb-4">Cordova Water System Inc.</a>
                <h1 class="text-xl font-bold text-slate-900">Sign in to your account</h1>
                <p class="text-slate-600 text-sm mt-1">Use Facebook or Google to continue</p>
            </div>

            <?php if ($errorMsg ?? $loginError ?? null): ?>
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm"><?= htmlspecialchars($loginError ?? $errorMsg) ?></div>
            <?php endif; ?>

            <form method="POST" class="mb-6 space-y-3">
                <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-3 rounded-xl border border-slate-200" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-3 rounded-xl border border-slate-200">
                <button type="submit" class="w-full py-3 px-4 rounded-xl font-semibold text-white bg-primary hover:bg-primary-dark">Sign in with Email</button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                <div class="relative flex justify-center text-sm"><span class="px-2 bg-white text-slate-500">Or continue with</span></div>
            </div>

            <div class="space-y-3">
                <?php if ($facebookUrl): ?>
                <a href="<?= htmlspecialchars($facebookUrl) ?>" class="flex items-center justify-center gap-3 w-full py-3 px-4 rounded-xl font-semibold bg-[#1877F2] text-white hover:bg-[#166FE5] transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    Continue with Facebook
                </a>
                <?php endif; ?>

                <?php if ($googleUrl): ?>
                <a href="<?= htmlspecialchars($googleUrl) ?>" class="flex items-center justify-center gap-3 w-full py-3 px-4 rounded-xl font-semibold border-2 border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Continue with Google
                </a>
                <?php endif; ?>

                <?php if (!$googleUrl && !$facebookUrl): ?>
                <div class="p-4 rounded-lg bg-amber-50 text-amber-800 text-sm">
                    Social login is not configured. Add GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, FB_APP_ID, FB_APP_SECRET to your .env file.
                </div>
                <?php endif; ?>
            </div>

            <p class="text-center text-slate-500 text-xs mt-6">
                By signing in, you agree to our terms of service.
            </p>
        </div>

        <p class="text-center mt-6">
            <a href="<?= BASE_URL ?>/" class="text-primary hover:underline text-sm">← Back to home</a>
        </p>
    </div>
</body>
</html>
