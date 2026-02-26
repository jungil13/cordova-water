<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

require_once __DIR__ . '/../includes/db.php';

// Stats
$stmt = $pdo->query("SELECT COUNT(*) FROM service_requests WHERE status = 'pending'");
$pendingRequests = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM service_requests WHERE status = 'in_progress'");
$inProgressRequests = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM billing WHERE status = 'unpaid'");
$unpaidBills = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'confirmed' AND MONTH(created_at) = MONTH(CURRENT_DATE())");
$monthlyRevenue = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT * FROM service_requests ORDER BY created_at DESC LIMIT 5");
$recentRequests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cordova Water System Inc.</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68',dark:'#083A4D'}}}}}</script>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">
<?php include __DIR__ . '/../includes/header.php'; ?>

<section class="pt-32 pb-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Admin Dashboard</h1>
            <p class="text-slate-600 mt-1">Monitor billing, payments, and service requests</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <a href="<?= BASE_URL ?>/admin/requests.php?status=pending" class="block p-6 rounded-2xl bg-white border border-slate-100 hover:border-amber-300 hover:shadow-lg transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">Pending Requests</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1"><?= $pendingRequests ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </a>
            <a href="<?= BASE_URL ?>/admin/requests.php?status=in_progress" class="block p-6 rounded-2xl bg-white border border-slate-100 hover:border-blue-300 hover:shadow-lg transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">In Progress</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1"><?= $inProgressRequests ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                </div>
            </a>
            <a href="<?= BASE_URL ?>/admin/billing.php" class="block p-6 rounded-2xl bg-white border border-slate-100 hover:border-red-300 hover:shadow-lg transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">Unpaid Bills</p>
                        <p class="text-3xl font-bold text-red-600 mt-1"><?= $unpaidBills ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
            </a>
            <div class="block p-6 rounded-2xl bg-white border border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">Revenue (This Month)</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">₱<?= number_format($monthlyRevenue, 0) ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-bold text-slate-900">Recent Service Requests</h2>
                    <a href="<?= BASE_URL ?>/admin/requests.php" class="text-primary text-sm font-medium hover:underline">View All</a>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php if (empty($recentRequests)): ?>
                    <div class="p-8 text-center text-slate-500">No service requests yet.</div>
                    <?php else: foreach ($recentRequests as $r): ?>
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-slate-900"><?= htmlspecialchars($r['fullname']) ?></p>
                            <p class="text-sm text-slate-500"><?= htmlspecialchars($r['service_type']) ?> • <?= date('M j, Y', strtotime($r['created_at'])) ?></p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium <?= $r['status'] === 'completed' ? 'bg-green-100 text-green-700' : ($r['status'] === 'in_progress' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') ?>"><?= ucfirst(str_replace('_', ' ', $r['status'])) ?></span>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="font-bold text-slate-900">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="<?= BASE_URL ?>/admin/requests.php" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 hover:bg-primary/5 border border-slate-100 hover:border-primary/20 transition-all">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <span class="font-medium">Manage Service Requests</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/billing.php" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 hover:bg-primary/5 border border-slate-100 hover:border-primary/20 transition-all">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="font-medium">Manage Billing</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/payments.php" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 hover:bg-primary/5 border border-slate-100 hover:border-primary/20 transition-all">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <span class="font-medium">Manage Payments</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= BASE_URL ?>/js/main.js"></script>
<script>const h=document.getElementById('main-header');window.addEventListener('scroll',()=>h?.classList.toggle('scrolled',window.scrollY>50));</script>
</body>
</html>
