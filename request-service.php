<?php
require_once __DIR__ . '/includes/auth.php';

$success = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/includes/db.php';
    
    $fullname = trim($_POST['fullname'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $serviceType = trim($_POST['service_type'] ?? '');
    $details = trim($_POST['details'] ?? '');
    $userId = $_SESSION['user_id'] ?? null;
    
    if (empty($fullname) || empty($address) || empty($contact) || empty($serviceType)) {
        $error = 'Please fill in all required fields.';
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO service_requests (user_id, fullname, address, contact, service_type, details) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $fullname, $address, $contact, $serviceType, $details ?: null]);
            $success = true;
        } catch (PDOException $e) {
            $error = 'Failed to submit request. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Service - Cordova Water System Inc.</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68',dark:'#083A4D'}}}}}</script>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">
<?php include __DIR__ . '/includes/header.php'; ?>

<section class="pt-32 pb-16 bg-gradient-to-br from-primary via-primary-dark to-slate-900 relative">
    <div class="absolute inset-0 bg-[url('images/background.jpg')] bg-cover bg-center opacity-25"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
    <div class="relative max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-6">Service Request</h1>
        <p class="text-lg text-white/90">Fill out the form below. Our team will get back to you as soon as possible.</p>
    </div>
</section>

<section class="py-24 -mt-16 relative z-10">
    <div class="max-w-2xl mx-auto px-4">
        <?php if ($success): ?>
        <div class="bg-white rounded-3xl shadow-xl p-10 text-center border border-green-200">
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mb-2">Request Submitted!</h2>
            <p class="text-slate-600 mb-6">Our team will review your request and contact you shortly.</p>
            <a href="<?= BASE_URL ?>/dashboard/" class="inline-flex px-6 py-3 rounded-xl font-semibold text-white bg-primary hover:bg-primary-dark">View Dashboard</a>
        </div>
        <?php else: ?>
        <form method="POST" action="" class="bg-white rounded-3xl shadow-xl p-8 md:p-10 border border-slate-100">
            <?php if ($error): ?><div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php 
            $user = currentUser();
            $prefillName = $user ? $user['name'] : '';
            $prefillContact = $user ? ($user['phone'] ?? '') : '';
            $prefillAddress = $user ? ($user['address'] ?? '') : '';
            ?>
            <div class="space-y-6">
                <div>
                    <label for="fullname" class="block text-sm font-semibold text-slate-700 mb-2">Full Name *</label>
                    <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($_POST['fullname'] ?? $prefillName) ?>" placeholder="Enter your full name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label for="address" class="block text-sm font-semibold text-slate-700 mb-2">Service Address *</label>
                    <input type="text" id="address" name="address" value="<?= htmlspecialchars($_POST['address'] ?? $prefillAddress) ?>" placeholder="Enter your complete address" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label for="contact" class="block text-sm font-semibold text-slate-700 mb-2">Contact Number *</label>
                    <input type="tel" id="contact" name="contact" value="<?= htmlspecialchars($_POST['contact'] ?? $prefillContact) ?>" placeholder="e.g., 0912 345 6789" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label for="service_type" class="block text-sm font-semibold text-slate-700 mb-2">Type of Request *</label>
                    <select id="service_type" name="service_type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white">
                        <option value="">-- Select Service --</option>
                        <option value="New Water Connection" <?= ($_POST['service_type'] ?? '') === 'New Water Connection' ? 'selected' : '' ?>>New Water Connection</option>
                        <option value="Reconnection" <?= ($_POST['service_type'] ?? '') === 'Reconnection' ? 'selected' : '' ?>>Reconnection</option>
                        <option value="Leak Repair" <?= ($_POST['service_type'] ?? '') === 'Leak Repair' ? 'selected' : '' ?>>Leak Repair</option>
                        <option value="Meter Installation" <?= ($_POST['service_type'] ?? '') === 'Meter Installation' ? 'selected' : '' ?>>Meter Installation</option>
                        <option value="Billing Concern" <?= ($_POST['service_type'] ?? '') === 'Billing Concern' ? 'selected' : '' ?>>Billing Concern</option>
                        <option value="Other" <?= ($_POST['service_type'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div>
                    <label for="details" class="block text-sm font-semibold text-slate-700 mb-2">Additional Details</label>
                    <textarea id="details" name="details" rows="4" placeholder="Mention any specific details here..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 resize-none"><?= htmlspecialchars($_POST['details'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="w-full py-4 rounded-xl font-semibold text-white bg-primary hover:bg-primary-dark flex items-center justify-center gap-2">
                    Submit Request
                </button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</section>

<footer class="bg-slate-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6">
        <span class="font-bold text-lg">Cordova Water System Inc.</span>
        <p class="text-slate-400 text-sm">© 2026 Cordova Water System Inc. All Rights Reserved.</p>
    </div>
</footer>

<script src="<?= BASE_URL ?>/js/main.js"></script>
<script>const h=document.getElementById('main-header');window.addEventListener('scroll',()=>h?.classList.toggle('scrolled',window.scrollY>50));</script>
</body>
</html>
