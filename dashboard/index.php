<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$user = currentUser();
require_once __DIR__ . '/../includes/db.php';

// User's service requests
$stmt = $pdo->prepare("SELECT * FROM service_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt->execute([$user['id']]);
$requests = $stmt->fetchAll();

// User's billing
$stmt = $pdo->prepare("SELECT * FROM billing WHERE user_id = ? ORDER BY period_end DESC LIMIT 5");
$stmt->execute([$user['id']]);
$billing = $stmt->fetchAll();

// User's payments
$stmt = $pdo->prepare("SELECT p.*, b.period_end FROM payments p LEFT JOIN billing b ON p.billing_id = b.id WHERE p.user_id = ? ORDER BY p.created_at DESC LIMIT 5");
$stmt->execute([$user['id']]);
$payments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cordova Water System Inc.</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68',dark:'#083A4D'}}}}}</script>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">
<?php include __DIR__ . '/../includes/header.php'; ?>

<section class="pt-32 pb-24">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Welcome back, <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?>!</h1>
            <p class="text-slate-600 mt-1">Manage your water service and billing</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-12">
            <a href="<?= BASE_URL ?>/request-service.php" class="block p-6 rounded-2xl bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl transition-all">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <h2 class="font-bold text-slate-900">New Service Request</h2>
                <p class="text-slate-600 text-sm mt-1">Submit a request for water service</p>
            </a>
            <a href="<?= BASE_URL ?>/payment.php" class="block p-6 rounded-2xl bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl transition-all">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h2 class="font-bold text-slate-900">Pay Bill</h2>
                <p class="text-slate-600 text-sm mt-1">Pay your water bill online</p>
            </a>
            <a href="<?= BASE_URL ?>/contact.php" class="block p-6 rounded-2xl bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl transition-all">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="font-bold text-slate-900">Contact Us</h2>
                <p class="text-slate-600 text-sm mt-1">Get in touch with our team</p>
            </a>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-bold text-slate-900">Service Requests</h2>
                    <a href="<?= BASE_URL ?>/request-service.php" class="text-primary text-sm font-medium hover:underline">New Request</a>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php if (empty($requests)): ?>
                    <div class="p-8 text-center text-slate-500">No service requests yet. <a href="<?= BASE_URL ?>/request-service.php" class="text-primary hover:underline">Submit one</a></div>
                    <?php else: foreach ($requests as $r): ?>
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-slate-900"><?= htmlspecialchars($r['service_type']) ?></p>
                            <p class="text-sm text-slate-500"><?= date('M j, Y', strtotime($r['created_at'])) ?></p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium <?= $r['status'] === 'completed' ? 'bg-green-100 text-green-700' : ($r['status'] === 'in_progress' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') ?>"><?= ucfirst(str_replace('_', ' ', $r['status'])) ?></span>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="font-bold text-slate-900">Recent Billing</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php if (empty($billing)): ?>
                    <div class="p-8 text-center text-slate-500">No billing records yet.</div>
                    <?php else: foreach ($billing as $b): ?>
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-slate-900"><?= date('M Y', strtotime($b['period_end'])) ?></p>
                            <p class="text-sm text-slate-500"><?= $b['consumption_cbm'] ?> m³</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-primary">₱<?= number_format($b['amount'], 2) ?></p>
                            <span class="text-xs <?= $b['status'] === 'paid' ? 'text-green-600' : 'text-amber-600' ?>"><?= ucfirst($b['status']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= BASE_URL ?>/js/main.js"></script>
<script>const h=document.getElementById('main-header');window.addEventListener('scroll',()=>h?.classList.toggle('scrolled',window.scrollY>50));</script>
</body>
</html>
