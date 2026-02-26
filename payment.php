<?php require_once __DIR__ . '/includes/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Options - Cordova Water System Inc.</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68',dark:'#083A4D'}}}}}</script>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="pt-32 pb-16 bg-gradient-to-br from-primary via-primary-dark to-slate-900 relative">
    <div class="absolute inset-0 bg-[url('<?= BASE_URL ?>/images/background.jpg')] bg-cover bg-center opacity-25"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
    <div class="relative max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-6">Payment Options</h1>
        <p class="text-lg text-white/90">Choose your preferred payment method. We accept online and cash payments.</p>
    </div>
</section>
<section class="py-24 -mt-16 relative z-10">
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-slate-100"><h2 class="text-2xl font-bold text-slate-900 mb-2">Online Payment</h2><p class="text-slate-600 mb-6">Pay through GCash or Palawan. Scan the QR codes.</p><div class="flex gap-4 justify-center"><img src="<?= BASE_URL ?>/images/palawan.png" alt="Palawan" class="w-36 h-36 object-contain rounded-xl border" onerror="this.style.display='none'"><img src="<?= BASE_URL ?>/images/gcash.jpg" alt="GCash" class="w-36 h-36 object-contain rounded-xl border" onerror="this.style.display='none'"></div></div>
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-slate-100"><h2 class="text-2xl font-bold text-slate-900 mb-2">Cash Payment</h2><p class="text-slate-600 mb-6">Visit our office or contact us for payment centers.</p><a href="<?= BASE_URL ?>/contact.php" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white bg-primary hover:bg-primary-dark">Contact for Instructions</a></div>
        </div>
    </div>
</section>
<footer class="bg-slate-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6">
        <span class="font-bold text-lg">Cordova Water System Inc.</span>
        <p class="text-slate-400 text-sm">© 2026 Cordova Water System Inc. All Rights Reserved.</p>
    </div>
</footer>
<script src="<?= BASE_URL ?>/js/main.js"></script>
</body>
</html>
