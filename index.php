<?php
require_once __DIR__ . '/includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cordova Water System Inc. | Reliable Water Services</title>
    <meta name="description" content="Cordova Water System Inc. - Ensuring clean water for a healthier tomorrow.">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68',dark:'#083A4D'},accent:'#00D9FF'}}}}</script>
    <style>.reveal{opacity:0;transform:translateY(24px);transition:all .6s}.reveal.visible{opacity:1;transform:translateY(0)}.hero-pattern{background-image:radial-gradient(circle at 20% 80%,rgba(0,217,255,.08) 0%,transparent 50%)}html{scroll-behavior:smooth}</style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">
<?php include __DIR__ . '/includes/header.php'; ?>

<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-primary via-primary-dark to-slate-900 hero-pattern">
    <div class="absolute inset-0 bg-[url('<?= BASE_URL ?>/images/background.jpg')] bg-cover bg-center opacity-30"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent"></div>
    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 text-center pt-20">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-8">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            <span class="text-sm font-medium text-white/90">Trusted by Cordova Community</span>
        </div>
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-white mb-6 leading-tight">
            Cordova Water<br><span class="text-accent">System Inc.</span>
        </h1>
        <p class="text-lg sm:text-xl text-white/90 max-w-2xl mx-auto mb-10">Ensuring clean water for a healthier tomorrow. We provide reliable water services to the Cordova community with a commitment to quality and sustainability.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= BASE_URL ?>/request-service.php" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl font-semibold text-primary bg-white hover:bg-slate-100 shadow-xl">Request Service</a>
            <a href="<?= BASE_URL ?>/plans.php" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl font-semibold text-white border-2 border-white/50 hover:bg-white/10">View Water Rates</a>
            <a href="<?= BASE_URL ?>/contact.php" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl font-semibold text-white/90 hover:text-white">Contact Us</a>
        </div>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal"><h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Reliable Water Services</h2><p class="text-slate-600 max-w-2xl mx-auto">Committed to delivering clean, safe water with exceptional service standards.</p></div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="reveal p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary/20 hover:shadow-xl transition-all"><h3 class="text-lg font-bold text-slate-900 mb-2">Clean Water</h3><p class="text-slate-600">Purified water meeting the highest safety standards.</p></div>
            <div class="reveal p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary/20 hover:shadow-xl transition-all"><h3 class="text-lg font-bold text-slate-900 mb-2">24/7 Support</h3><p class="text-slate-600">Round-the-clock assistance for your water needs.</p></div>
            <div class="reveal p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary/20 hover:shadow-xl transition-all"><h3 class="text-lg font-bold text-slate-900 mb-2">Trusted Service</h3><p class="text-slate-600">Decades of experience serving Cordova community.</p></div>
            <div class="reveal p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary/20 hover:shadow-xl transition-all"><h3 class="text-lg font-bold text-slate-900 mb-2">Easy Payment</h3><p class="text-slate-600">GCash, Palawan, and cash for your convenience.</p></div>
        </div>
    </div>
</section>

<section class="py-20 bg-gradient-to-r from-primary to-primary-dark">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to Get Started?</h2>
        <p class="text-white/90 mb-8">Request a new connection or reach out for any water service needs.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?= BASE_URL ?>/request-service.php" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-white text-primary hover:bg-slate-100">Request Service</a>
            <a href="<?= BASE_URL ?>/contact.php" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white border-2 border-white/50 hover:bg-white/10">Contact Us</a>
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
<script>
document.querySelectorAll('.reveal').forEach((el,i)=>{const o=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible')})},{threshold:.1});o.observe(el)});
const h=document.getElementById('main-header');window.addEventListener('scroll',()=>h?.classList.toggle('scrolled',window.scrollY>50));
</script>
</body>
</html>
