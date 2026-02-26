<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/dashboard/');
    exit;
}

$loginError = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $user = loginUser($email, $password);
        if ($user) {
            $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . '/dashboard/';
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirect);
            exit;
        } else {
            $loginError = 'Invalid email or password.';
        }
    } else {
        $loginError = 'Please enter both email and password.';
    }
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
    <style>
        .auth-bg {
            background-image: linear-gradient(rgba(10, 77, 104, 0.8), rgba(8, 58, 77, 0.9)), url('images/background.jpg');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="font-sans antialiased auth-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border border-white/20">
            <div class="text-center mb-8">
                <a href="<?= BASE_URL ?>/" class="inline-block text-2xl font-bold text-primary mb-4">Cordova Water System Inc.</a>
                <h1 class="text-xl font-bold text-slate-900">Sign in to your account</h1>
                <p class="text-slate-600 text-sm mt-1">Enter your credentials to continue</p>
            </div>

            <?php if ($loginError): ?>
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <?= htmlspecialchars($loginError) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="name@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="••••••••">
                </div>
                <button type="submit" class="w-full py-3 px-4 rounded-xl font-semibold text-white bg-primary hover:bg-primary-dark shadow-lg shadow-primary/20 transition-all active:scale-[0.98]">
                    Sign in
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-slate-600 text-sm">Don't have an account? <a href="register.php" class="text-primary font-bold hover:underline">Register here</a></p>
            </div>
        </div>

        <p class="text-center mt-6">
            <a href="<?= BASE_URL ?>/" class="text-white/80 hover:text-white transition-colors text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to home
            </a>
        </p>
    </div>
</body>
</html>
