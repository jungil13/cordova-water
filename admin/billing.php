<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

require_once __DIR__ . '/../includes/db.php';

// Add billing if POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $userId = !empty($_POST['user_id']) ? (int)$_POST['user_id'] : null;
        $accountNumber = trim($_POST['account_number'] ?? '');
        $periodStart = $_POST['period_start'] ?? '';
        $periodEnd = $_POST['period_end'] ?? '';
        $consumption = (float)($_POST['consumption'] ?? 0);
        $amount = (float)($_POST['amount'] ?? 0);
        $dueDate = $_POST['due_date'] ?? null;
        
        if ($periodStart && $periodEnd) {
            $stmt = $pdo->prepare("INSERT INTO billing (user_id, account_number, period_start, period_end, consumption_cbm, amount, due_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$userId ?: null, $accountNumber ?: null, $periodStart, $periodEnd, $consumption, $amount, $dueDate ?: null]);
        }
    } elseif ($_POST['action'] === 'update_status' && isset($_POST['id'], $_POST['status'])) {
        $id = (int)$_POST['id'];
        $newStatus = $_POST['status'];
        if (in_array($newStatus, ['unpaid', 'paid', 'overdue'])) {
            $stmt = $pdo->prepare("UPDATE billing SET status = ?, paid_at = " . ($newStatus === 'paid' ? "NOW()" : "NULL") . " WHERE id = ?");
            $stmt->execute([$newStatus, $id]);
        }
    }
    header('Location: ' . BASE_URL . '/admin/billing.php');
    exit;
}

$stmt = $pdo->query("
    SELECT b.*, u.name as user_name, u.email 
    FROM billing b 
    LEFT JOIN users u ON b.user_id = u.id 
    ORDER BY b.period_end DESC 
    LIMIT 50
");
$billing = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing - Admin - Cordova Water System Inc.</title>
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
                <h1 class="text-3xl font-bold text-slate-900">Billing</h1>
                <p class="text-slate-600 mt-1">Manage water consumption and billing records</p>
            </div>
            <button onclick="document.getElementById('add-billing-modal').classList.remove('hidden')" class="px-4 py-2 rounded-xl font-semibold text-white bg-primary hover:bg-primary-dark">+ Add Billing</button>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
            <?php if (empty($billing)): ?>
            <div class="p-12 text-center text-slate-500">No billing records yet. <button onclick="document.getElementById('add-billing-modal').classList.remove('hidden')" class="text-primary hover:underline">Add one</button></div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left p-4 font-semibold text-slate-700">Period</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Customer</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Consumption</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Amount</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Status</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($billing as $b): ?>
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                            <td class="p-4 text-sm"><?= date('M j', strtotime($b['period_start'])) ?> - <?= date('M j, Y', strtotime($b['period_end'])) ?></td>
                            <td class="p-4">
                                <p class="font-medium"><?= htmlspecialchars($b['user_name'] ?? 'Guest') ?></p>
                                <?php if ($b['account_number']): ?><p class="text-sm text-slate-500"><?= htmlspecialchars($b['account_number']) ?></p><?php endif; ?>
                            </td>
                            <td class="p-4"><?= number_format($b['consumption_cbm'], 2) ?> m³</td>
                            <td class="p-4 font-bold text-primary">₱<?= number_format($b['amount'], 2) ?></td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium <?= $b['status'] === 'paid' ? 'bg-green-100 text-green-700' : ($b['status'] === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') ?>"><?= ucfirst($b['status']) ?></span>
                            </td>
                            <td class="p-4">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                    <select name="status" onchange="this.form.submit()" class="text-sm px-2 py-1 rounded border border-slate-200">
                                        <option value="unpaid" <?= $b['status'] === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                        <option value="paid" <?= $b['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                                        <option value="overdue" <?= $b['status'] === 'overdue' ? 'selected' : '' ?>>Overdue</option>
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

<!-- Add Billing Modal -->
<div id="add-billing-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold text-slate-900 mb-6">Add Billing Record</h2>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="action" value="add">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Account Number</label>
                <input type="text" name="account_number" placeholder="Optional" class="w-full px-4 py-2 rounded-lg border border-slate-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Period Start *</label>
                <input type="date" name="period_start" required class="w-full px-4 py-2 rounded-lg border border-slate-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Period End *</label>
                <input type="date" name="period_end" required class="w-full px-4 py-2 rounded-lg border border-slate-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Consumption (m³) *</label>
                <input type="number" step="0.01" name="consumption" required class="w-full px-4 py-2 rounded-lg border border-slate-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Amount (₱) *</label>
                <input type="number" step="0.01" name="amount" required class="w-full px-4 py-2 rounded-lg border border-slate-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Due Date</label>
                <input type="date" name="due_date" class="w-full px-4 py-2 rounded-lg border border-slate-200">
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 py-2 rounded-xl font-semibold text-white bg-primary hover:bg-primary-dark">Add</button>
                <button type="button" onclick="document.getElementById('add-billing-modal').classList.add('hidden')" class="flex-1 py-2 rounded-xl font-semibold border border-slate-200 text-slate-700">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= BASE_URL ?>/js/main.js"></script>
<script>const h=document.getElementById('main-header');window.addEventListener('scroll',()=>h?.classList.toggle('scrolled',window.scrollY>50));</script>
</body>
</html>
