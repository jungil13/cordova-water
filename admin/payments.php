<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

require_once __DIR__ . '/../includes/db.php';

$user = currentUser();

// Update payment status if POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = (int)$_POST['id'];
    $newStatus = $_POST['status'];
    if (in_array($newStatus, ['pending', 'confirmed', 'failed'])) {
        $stmt = $pdo->prepare("UPDATE payments SET status = ?, confirmed_by = ? WHERE id = ?");
        $stmt->execute([$newStatus, $user['id'], $id]);
    }
    header('Location: ' . BASE_URL . '/admin/payments.php');
    exit;
}

// Add payment if POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $userId = !empty($_POST['user_id']) ? (int)$_POST['user_id'] : null;
    $billingId = !empty($_POST['billing_id']) ? (int)$_POST['billing_id'] : null;
    $amount = (float)($_POST['amount'] ?? 0);
    $method = $_POST['method'] ?? 'cash';
    $reference = trim($_POST['reference'] ?? '');
    
    if ($amount > 0) {
        $stmt = $pdo->prepare("INSERT INTO payments (user_id, billing_id, amount, method, reference, status, confirmed_by) VALUES (?, ?, ?, ?, ?, 'confirmed', ?)");
        $stmt->execute([$userId, $billingId ?: null, $amount, $method, $reference ?: null, $user['id']]);
        if ($billingId) {
            $pdo->prepare("UPDATE billing SET status = 'paid', paid_at = NOW() WHERE id = ?")->execute([$billingId]);
        }
    }
    header('Location: ' . BASE_URL . '/admin/payments.php');
    exit;
}

$stmt = $pdo->query("
    SELECT p.*, u.name as user_name, b.period_end 
    FROM payments p 
    LEFT JOIN users u ON p.user_id = u.id 
    LEFT JOIN billing b ON p.billing_id = b.id 
    ORDER BY p.created_at DESC 
    LIMIT 50
");
$payments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Admin - Cordova Water System Inc.</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68',dark:'#083A4D'}}}}}</script>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">
<?php include __DIR__ . '/../includes/header.php'; ?>

<section class="pt-32 pb-24">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Payments</h1>
                <p class="text-slate-600 mt-1">Monitor and confirm payment records</p>
            </div>
            <button onclick="document.getElementById('add-payment-modal').classList.remove('hidden')" class="px-4 py-2 rounded-xl font-semibold text-white bg-primary hover:bg-primary-dark">+ Record Payment</button>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
            <?php if (empty($payments)): ?>
            <div class="p-12 text-center text-slate-500">No payments yet. <button onclick="document.getElementById('add-payment-modal').classList.remove('hidden')" class="text-primary hover:underline">Record one</button></div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left p-4 font-semibold text-slate-700">Date</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Customer</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Amount</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Method</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Reference</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Status</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $p): ?>
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                            <td class="p-4 text-sm"><?= date('M j, Y H:i', strtotime($p['created_at'])) ?></td>
                            <td class="p-4"><?= htmlspecialchars($p['user_name'] ?? 'Guest') ?></td>
                            <td class="p-4 font-bold text-primary">₱<?= number_format($p['amount'], 2) ?></td>
                            <td class="p-4 capitalize"><?= htmlspecialchars($p['method']) ?></td>
                            <td class="p-4 text-sm text-slate-600"><?= htmlspecialchars($p['reference'] ?? '-') ?></td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium <?= $p['status'] === 'confirmed' ? 'bg-green-100 text-green-700' : ($p['status'] === 'failed' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') ?>"><?= ucfirst($p['status']) ?></span>
                            </td>
                            <td class="p-4">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <select name="status" onchange="this.form.submit()" class="text-sm px-2 py-1 rounded border border-slate-200">
                                        <option value="pending" <?= $p['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="confirmed" <?= $p['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                        <option value="failed" <?= $p['status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Add Payment Modal -->
<div id="add-payment-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full">
        <h2 class="text-xl font-bold text-slate-900 mb-6">Record Payment</h2>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="action" value="add">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Amount (₱) *</label>
                <input type="number" step="0.01" name="amount" required class="w-full px-4 py-2 rounded-lg border border-slate-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Payment Method *</label>
                <select name="method" required class="w-full px-4 py-2 rounded-lg border border-slate-200">
                    <option value="gcash">GCash</option>
                    <option value="palawan">Palawan</option>
                    <option value="cash">Cash</option>
                    <option value="bank">Bank Transfer</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Reference / Receipt #</label>
                <input type="text" name="reference" placeholder="Optional" class="w-full px-4 py-2 rounded-lg border border-slate-200">
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 py-2 rounded-xl font-semibold text-white bg-primary hover:bg-primary-dark">Record</button>
                <button type="button" onclick="document.getElementById('add-payment-modal').classList.add('hidden')" class="flex-1 py-2 rounded-xl font-semibold border border-slate-200 text-slate-700">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= BASE_URL ?>/js/main.js"></script>
<script>const h=document.getElementById('main-header');window.addEventListener('scroll',()=>h?.classList.toggle('scrolled',window.scrollY>50));</script>
</body>
</html>
