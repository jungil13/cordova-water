<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

require_once __DIR__ . '/../includes/db.php';

$status = $_GET['status'] ?? null;
$user = currentUser();

// Update status if POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = (int)$_POST['id'];
    $newStatus = $_POST['status'];
    if (in_array($newStatus, ['pending', 'in_progress', 'completed', 'cancelled'])) {
        $notes = trim($_POST['admin_notes'] ?? '');
        $stmt = $pdo->prepare("UPDATE service_requests SET status = ?, admin_notes = ?, assigned_to = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$newStatus, $notes, $user['id'], $id]);
    }
    header('Location: ' . BASE_URL . '/admin/requests.php' . ($status ? '?status=' . $status : ''));
    exit;
}

// Fetch requests
$sql = "SELECT * FROM service_requests";
$params = [];
if ($status && in_array($status, ['pending', 'in_progress', 'completed', 'cancelled'])) {
    $sql .= " WHERE status = ?";
    $params[] = $status;
}
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$requests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Requests - Admin - Cordova Water System Inc.</title>
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
                <h1 class="text-3xl font-bold text-slate-900">Service Requests</h1>
                <p class="text-slate-600 mt-1">Manage and process service requests</p>
            </div>
            <div class="flex gap-2">
                <a href="<?= BASE_URL ?>/admin/requests.php" class="px-4 py-2 rounded-lg font-medium <?= !$status ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">All</a>
                <a href="<?= BASE_URL ?>/admin/requests.php?status=pending" class="px-4 py-2 rounded-lg font-medium <?= $status === 'pending' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">Pending</a>
                <a href="<?= BASE_URL ?>/admin/requests.php?status=in_progress" class="px-4 py-2 rounded-lg font-medium <?= $status === 'in_progress' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">In Progress</a>
                <a href="<?= BASE_URL ?>/admin/requests.php?status=completed" class="px-4 py-2 rounded-lg font-medium <?= $status === 'completed' ? 'bg-green-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">Completed</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
            <?php if (empty($requests)): ?>
            <div class="p-12 text-center text-slate-500">No service requests found.</div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left p-4 font-semibold text-slate-700">Date</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Name</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Type</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Contact</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Status</th>
                            <th class="text-left p-4 font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $r): ?>
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                            <td class="p-4 text-sm text-slate-600"><?= date('M j, Y H:i', strtotime($r['created_at'])) ?></td>
                            <td class="p-4">
                                <p class="font-medium text-slate-900"><?= htmlspecialchars($r['fullname']) ?></p>
                                <p class="text-sm text-slate-500"><?= htmlspecialchars($r['address']) ?></p>
                            </td>
                            <td class="p-4 font-medium"><?= htmlspecialchars($r['service_type']) ?></td>
                            <td class="p-4"><?= htmlspecialchars($r['contact']) ?></td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium <?= $r['status'] === 'completed' ? 'bg-green-100 text-green-700' : ($r['status'] === 'in_progress' ? 'bg-amber-100 text-amber-700' : ($r['status'] === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700')) ?>"><?= ucfirst(str_replace('_', ' ', $r['status'])) ?></span>
                            </td>
                            <td class="p-4">
                                <details class="relative">
                                    <summary class="cursor-pointer px-3 py-1 rounded-lg bg-primary/10 text-primary font-medium text-sm hover:bg-primary/20">Update</summary>
                                    <div class="absolute right-0 mt-1 p-4 bg-white rounded-xl shadow-xl border border-slate-200 z-10 min-w-[280px]">
                                        <form method="POST" class="space-y-3">
                                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                                                <select name="status" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm">
                                                    <option value="pending" <?= $r['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="in_progress" <?= $r['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                                    <option value="completed" <?= $r['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                    <option value="cancelled" <?= $r['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                                                <textarea name="admin_notes" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm" placeholder="Internal notes..."><?= htmlspecialchars($r['admin_notes'] ?? '') ?></textarea>
                                            </div>
                                            <button type="submit" class="w-full py-2 rounded-lg font-medium text-white bg-primary hover:bg-primary-dark text-sm">Save</button>
                                        </form>
                                    </div>
                                </details>
                                <?php if ($r['details']): ?>
                                <p class="mt-2 text-xs text-slate-500 max-w-[200px]" title="<?= htmlspecialchars($r['details']) ?>"><?= htmlspecialchars(substr($r['details'], 0, 50)) ?>...</p>
                                <?php endif; ?>
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

<script src="<?= BASE_URL ?>/js/main.js"></script>
<script>const h=document.getElementById('main-header');window.addEventListener('scroll',()=>h?.classList.toggle('scrolled',window.scrollY>50));</script>
</body>
</html>
