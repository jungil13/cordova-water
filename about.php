<?php require_once __DIR__ . '/includes/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Cordova Water System Inc.</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68',dark:'#083A4D'},accent:'#00D9FF'}}}}</script>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="pt-32 pb-20 bg-gradient-to-br from-primary via-primary-dark to-slate-900 relative">
    <div class="absolute inset-0 bg-[url('images/background.jpg')] bg-cover bg-center opacity-25"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
    <div class="relative max-w-4xl mx-auto px-4 text-center">
        <p class="text-accent font-semibold text-sm uppercase tracking-wider mb-2">A Subsidiary of Abejo Waters Corp.</p>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-6">About Cordova Water System</h1>
        <p class="text-lg text-white/90 max-w-2xl mx-auto">We provide high-quality water services with an emphasis on sustainability and innovation. Our mission is to ensure every household in Cordova has access to clean, safe, and affordable water.</p>
        <div class="flex flex-wrap justify-center gap-4 mt-8">
            <a href="<?= BASE_URL ?>/services.php" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-primary bg-white hover:bg-slate-100">Our Services</a>
            <a href="<?= BASE_URL ?>/contact.php" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white border-2 border-white/50 hover:bg-white/10">Learn More</a>
        </div>
    </div>
</section>
<section class="py-24 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div><h2 class="text-2xl font-bold text-slate-900 mb-4">Our Mission</h2><p class="text-slate-600 leading-relaxed">Delivering reliable, clean water to every household in Cordova while maintaining the highest standards of service and sustainability.</p></div>
            <div><h2 class="text-2xl font-bold text-slate-900 mb-4">Our Vision</h2><p class="text-slate-600 leading-relaxed">To be the most trusted water service provider in Cordova, known for excellence, innovation, and unwavering dedication to community well-being.</p></div>
        </div>
    </div>
</section>
<section class="py-24 bg-slate-50">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-slate-900 mb-12 text-center">Our Team</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="relative w-20 h-20 rounded-full mx-auto mb-3 overflow-hidden bg-primary/10 ring-2 ring-primary/20">
                    <img src="images/team/admin-thumb.jpg" alt="Admin" class="absolute inset-0 w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.classList.remove('hidden');">
                    <span class="absolute inset-0 hidden flex items-center justify-center text-primary font-bold text-xl">A</span>
                </div>
                <h3 class="font-bold text-slate-900">Admin</h3>
            </div>
            <div class="text-center">
                <div class="relative w-20 h-20 rounded-full mx-auto mb-3 overflow-hidden bg-primary/10 ring-2 ring-primary/20">
                    <img src="images/team/billing-thumb.jpg" alt="Billing" class="absolute inset-0 w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.classList.remove('hidden');">
                    <span class="absolute inset-0 hidden flex items-center justify-center text-primary font-bold text-xl">B</span>
                </div>
                <h3 class="font-bold text-slate-900">Billing</h3>
            </div>
            <div class="text-center">
                <div class="relative w-20 h-20 rounded-full mx-auto mb-3 overflow-hidden bg-primary/10 ring-2 ring-primary/20">
                    <img src="images/team/maintenance-thumb.jpg" alt="Maintenance" class="absolute inset-0 w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.classList.remove('hidden');">
                    <span class="absolute inset-0 hidden flex items-center justify-center text-primary font-bold text-xl">M</span>
                </div>
                <h3 class="font-bold text-slate-900">Maintenance</h3>
            </div>
            <div class="text-center">
                <div class="relative w-20 h-20 rounded-full mx-auto mb-3 overflow-hidden bg-primary/10 ring-2 ring-primary/20">
                    <img src="images/team/manager-thumb.jpg" alt="Manager" class="absolute inset-0 w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.classList.remove('hidden');">
                    <span class="absolute inset-0 hidden flex items-center justify-center text-primary font-bold text-xl">M</span>
                </div>
                <h3 class="font-bold text-slate-900">Manager</h3>
            </div>
        </div>
    </div>
</section>
<section class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-slate-900 mb-12 text-center">What We Do</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="overflow-hidden rounded-2xl shadow-lg group">
                <img src="images/about-page/0aef5c6e-a75f-4435-a1f9-5ec93a83c415.jfif" alt="CWSI Action" class="w-full h-64 object-cover transform transition-transform duration-500 group-hover:scale-110">
            </div>
            <div class="overflow-hidden rounded-2xl shadow-lg group">
                <img src="images/about-page/3f5c4d01-f1d9-49ee-bf48-2e4ce5af8bcb.jfif" alt="CWSI Action" class="w-full h-64 object-cover transform transition-transform duration-500 group-hover:scale-110">
            </div>
            <div class="overflow-hidden rounded-2xl shadow-lg group">
                <img src="images/about-page/615ab07d-4b81-4c8c-b117-276a2aa068c7.jfif" alt="CWSI Action" class="w-full h-64 object-cover transform transition-transform duration-500 group-hover:scale-110">
            </div>
            <div class="overflow-hidden rounded-2xl shadow-lg group">
                <img src="images/about-page/6ec93be4-846d-484e-824b-17c38aad8540.jfif" alt="CWSI Action" class="w-full h-64 object-cover transform transition-transform duration-500 group-hover:scale-110">
            </div>
            <div class="overflow-hidden rounded-2xl shadow-lg group">
                <img src="images/about-page/9eec5991-f61c-4d87-8273-e1f5db0396a5.jfif" alt="CWSI Action" class="w-full h-64 object-cover transform transition-transform duration-500 group-hover:scale-110">
            </div>
            <div class="overflow-hidden rounded-2xl shadow-lg group">
                <img src="images/about-page/d2535bb8-0d07-46c7-b618-84e97dcda426.jfif" alt="CWSI Action" class="w-full h-64 object-cover transform transition-transform duration-500 group-hover:scale-110">
            </div>
            <div class="overflow-hidden rounded-2xl shadow-lg group">
                <img src="images/about-page/da72d666-017f-4807-a723-3bd018203e35.jfif" alt="CWSI Action" class="w-full h-64 object-cover transform transition-transform duration-500 group-hover:scale-110">
            </div>
            <div class="overflow-hidden rounded-2xl shadow-lg group">
                <img src="images/about-page/f45ea0d7-9b39-41ed-9a86-6ebaf9e42e06.jfif" alt="CWSI Action" class="w-full h-64 object-cover transform transition-transform duration-500 group-hover:scale-110">
            </div>
        </div>
    </div>
</section>

<footer class="bg-slate-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6">
        <span class="font-bold text-lg">Cordova Water System Inc.</span>
        <p class="text-slate-400 text-sm">Â© 2026 Cordova Water System Inc. All Rights Reserved.</p>
    </div>
</footer>
<script src="<?= BASE_URL ?>/js/main.js"></script>
</body>
</html>

