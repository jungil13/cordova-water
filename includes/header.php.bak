<?php
// Shared header - include after auth if needed
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$user = currentUser();
?>
<header id="main-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 [&.scrolled]:!bg-primary [&.scrolled]:border-primary/30">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <a href="<?= rtrim(BASE_URL,'/') ?>/" class="flex items-center gap-2 group">
                <img src="<?= BASE_URL ?>/images/logo.png" alt="Cordova Water" class="h-12" onerror="this.style.display='none'">
                <span class="brand-name text-xl font-bold text-primary">Cordova Water</span>
            </a>

            <ul class="hidden md:flex items-center gap-1">
                <li><a href="<?= BASE_URL ?>/index.php" class="nav-link px-4 py-2 rounded-lg font-medium <?= $currentPage === 'index' ? 'text-primary bg-primary/10' : 'text-slate-600 hover:text-primary hover:bg-slate-100' ?>">Home</a></li>
                <li><a href="<?= BASE_URL ?>/about.php" class="nav-link px-4 py-2 rounded-lg font-medium <?= $currentPage === 'about' ? 'text-primary bg-primary/10' : 'text-slate-600 hover:text-primary hover:bg-slate-100' ?>">About</a></li>
                <li><a href="<?= BASE_URL ?>/services.php" class="nav-link px-4 py-2 rounded-lg font-medium <?= $currentPage === 'services' ? 'text-primary bg-primary/10' : 'text-slate-600 hover:text-primary hover:bg-slate-100' ?>">Services</a></li>
                <li><a href="<?= BASE_URL ?>/contact.php" class="nav-link px-4 py-2 rounded-lg font-medium <?= $currentPage === 'contact' ? 'text-primary bg-primary/10' : 'text-slate-600 hover:text-primary hover:bg-slate-100' ?>">Contact</a></li>
                <li><a href="<?= BASE_URL ?>/payment.php" class="nav-link px-4 py-2 rounded-lg font-medium <?= $currentPage === 'payment' ? 'text-primary bg-primary/10' : 'text-slate-600 hover:text-primary hover:bg-slate-100' ?>">Payment</a></li>
                <?php if ($user): ?>
                <li><a href="<?= BASE_URL ?>/dashboard/" class="nav-link px-4 py-2 rounded-lg font-medium <?= $currentPage === 'dashboard' ? 'text-primary bg-primary/10' : 'text-slate-600 hover:text-primary hover:bg-slate-100' ?>">Dashboard</a></li>
                <?php if (isAdmin()): ?>
                <li><a href="<?= BASE_URL ?>/admin/" class="nav-link px-4 py-2 rounded-lg font-medium <?= strpos($_SERVER['REQUEST_URI'], '/admin') !== false ? 'text-primary bg-primary/10' : 'text-slate-600 hover:text-primary hover:bg-slate-100' ?>">Admin</a></li>
                <?php endif; ?>
                <li><a href="<?= BASE_URL ?>/logout.php" class="nav-link px-4 py-2 rounded-lg font-medium text-slate-600 hover:text-red-600">Logout</a></li>
                <?php else: ?>
                <li><a href="<?= BASE_URL ?>/request-service.php" class="nav-link-cta ml-2 px-5 py-2.5 rounded-xl font-semibold text-white bg-primary hover:bg-primary-dark">Request Service</a></li>
                <li><a href="<?= BASE_URL ?>/login.php" class="nav-link px-4 py-2 rounded-lg font-medium text-slate-600 hover:text-primary hover:bg-slate-100">Login</a></li>
                <?php endif; ?>
            </ul>

            <button id="mobile-menu-btn" class="md:hidden hamburger flex flex-col gap-1.5 p-2 rounded-lg" aria-label="Menu">
                <span class="block w-6 h-0.5 bg-slate-700 rounded transition-all duration-300"></span>
                <span class="block w-6 h-0.5 bg-slate-700 rounded transition-all duration-300"></span>
                <span class="block w-6 h-0.5 bg-slate-700 rounded transition-all duration-300"></span>
            </button>
        </div>
    </nav>

    <div id="mobile-nav" class="hidden md:hidden border-t border-slate-200 bg-white/95">
        <ul class="px-4 py-4 space-y-1">
            <li><a href="<?= BASE_URL ?>/index.php" class="block px-4 py-3 rounded-lg font-medium text-slate-600">Home</a></li>
            <li><a href="<?= BASE_URL ?>/about.php" class="block px-4 py-3 rounded-lg font-medium text-slate-600">About</a></li>
            <li><a href="<?= BASE_URL ?>/services.php" class="block px-4 py-3 rounded-lg font-medium text-slate-600">Services</a></li>
            <li><a href="<?= BASE_URL ?>/contact.php" class="block px-4 py-3 rounded-lg font-medium text-slate-600">Contact</a></li>
            <li><a href="<?= BASE_URL ?>/payment.php" class="block px-4 py-3 rounded-lg font-medium text-slate-600">Payment</a></li>
            <?php if ($user): ?>
            <li><a href="<?= BASE_URL ?>/dashboard/" class="block px-4 py-3 rounded-lg font-medium text-slate-600">Dashboard</a></li>
            <?php if (isAdmin()): ?><li><a href="<?= BASE_URL ?>/admin/" class="block px-4 py-3 rounded-lg font-medium text-slate-600">Admin</a></li><?php endif; ?>
            <li><a href="<?= BASE_URL ?>/logout.php" class="block px-4 py-3 rounded-lg font-medium text-red-600">Logout</a></li>
            <?php else: ?>
            <li><a href="<?= BASE_URL ?>/request-service.php" class="block mx-4 mt-4 py-3.5 rounded-xl font-semibold text-center text-white bg-primary">Request Service</a></li>
            <li><a href="<?= BASE_URL ?>/login.php" class="block px-4 py-3 rounded-lg font-medium text-slate-600">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('mobile-menu-btn');
    const nav = document.getElementById('mobile-nav');
    if (btn && nav) {
        btn.onclick = (e) => {
            e.preventDefault();
            btn.classList.toggle('active');
            nav.classList.toggle('hidden');
        };
    }
});
</script>
<style>
#main-header.scrolled .nav-link{color:rgba(255,255,255,0.9)}#main-header.scrolled .nav-link:hover{color:#fff;background:rgba(255,255,255,0.15)}#main-header.scrolled .nav-link.active,#main-header.scrolled .nav-link[class*="bg-primary"]{color:#fff;background:rgba(255,255,255,0.2)}#main-header.scrolled .nav-link-cta{background:#fff;color:#0A4D68}#main-header.scrolled .brand-name{color:#fff!important}.hamburger.active span:nth-child(1){transform:rotate(45deg) translate(5px,5px)}.hamburger.active span:nth-child(2){opacity:0}.hamburger.active span:nth-child(3){transform:rotate(-45deg) translate(5px,-5px)}
</style>
