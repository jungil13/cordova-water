<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/dashboard/');
    exit;
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $result = registerUser($name, $email, $password);
        if (isset($result['success'])) {
            $success = "Registration successful! You can now log in.";
        } else {
            $error = $result['error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cordova Water System Inc.</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68',dark:'#083A4D'}}}}}</script>
    <style>
        .auth-bg {
            background-image: linear-gradient(rgba(10, 77, 104, 0.85), rgba(8, 58, 77, 0.95)), url('images/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="font-sans antialiased auth-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20">
            <div class="text-center mb-10">
                <a href="<?= BASE_URL ?>/" class="inline-block text-2xl font-bold text-primary mb-2">Cordova Water System Inc.</a>
                <h1 class="text-xl font-extrabold text-slate-900">Create Account</h1>
                <p class="text-slate-500 text-sm mt-2">Join Cordova's digital water system</p>
            </div>

            <?php if ($error): ?>
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm flex items-center gap-2"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm">
                <?= htmlspecialchars($success) ?>
                <div class="mt-3"><a href="login.php" class="font-bold underline text-primary">Proceed to Login</a></div>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Full Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="Juan Dela Cruz" value="<?= htmlspecialchars($name ?? '') ?>">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="name@example.com" value="<?= htmlspecialchars($email ?? '') ?>">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Confirm</label>
                        <input type="password" name="confirm_password" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="w-full py-4 px-4 rounded-xl font-bold text-white bg-primary hover:bg-primary-dark shadow-lg shadow-primary/30 mt-4 transition-all transform active:scale-[0.98]">
                    Create Account
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-100 text-center">
                <p class="text-slate-500 text-sm">Already have an account? <a href="login.php" class="text-primary font-bold hover:underline">Login here</a></p>
            </div>
        </div>
        <p class="text-center mt-8">
            <a href="<?= BASE_URL ?>/" class="text-white/60 hover:text-white transition-colors text-sm font-medium flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to website
            </a>
        </p>
    </div>
</body>
</html>
