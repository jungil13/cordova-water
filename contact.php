<?php require_once __DIR__ . '/includes/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Cordova Water System Inc.</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68',dark:'#083A4D'}}}}}</script>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="pt-32 pb-16 bg-gradient-to-br from-primary via-primary-dark to-slate-900 relative">
    <div class="absolute inset-0 bg-[url('images/background.jpg')] bg-cover bg-center opacity-25"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
    <div class="relative max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-6">Contact Us</h1>
        <p class="text-lg text-white/90">We're here to help. Reach out for inquiries, billing concerns, or water service requests.</p>
    </div>
</section>
<section class="py-24 -mt-16 relative z-10">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="tel:09917023497" class="p-8 rounded-2xl bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl transition-all"><h3 class="font-bold text-slate-900 mb-1">Mobile</h3><p class="text-primary font-semibold">0991 702 3497</p></a>
            <a href="tel:0323833520" class="p-8 rounded-2xl bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl transition-all"><h3 class="font-bold text-slate-900 mb-1">Telephone</h3><p class="text-primary font-semibold">(032) 383 3520</p></a>
            <a href="https://www.facebook.com/profile.php?id=61562900730330" target="_blank" rel="noopener" class="p-8 rounded-2xl bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl transition-all"><h3 class="font-bold text-slate-900 mb-1">Facebook</h3><p class="text-primary font-semibold text-sm">Cordova Water System Inc.</p></a>
            <a href="mailto:cwsi@abejoph.com" class="p-8 rounded-2xl bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl transition-all"><h3 class="font-bold text-slate-900 mb-1">Email</h3><p class="text-primary font-semibold text-sm break-all">cwsi@abejoph.com</p></a>
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
