<?php
require_once __DIR__ . '/includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Cordova Water System Inc.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68'}}}}}</script>
</head>
<body class="bg-slate-50">
    <?php include __DIR__ . '/includes/header.php'; ?>
    <section class="pt-32 pb-24">
        <div class="max-w-4xl mx-auto px-4 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            <h1 class="text-3xl font-bold text-slate-900 mb-6">Privacy Policy</h1>
            <p class="text-slate-600 mb-4">Last updated: February 26, 2026</p>
            
            <h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">1. Information We Collect</h2>
            <p class="text-slate-600 mb-4">We collect information that you provide directly to us through the Cordova Water System Inc. website, including when you log in via Google, submit a service request, or upload payment receipts.</p>

            <h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">2. How We Use Your Information</h2>
            <p class="text-slate-600 mb-4">We use this information to manage your water service, process payments, and provide customer support.</p>

            <h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">3. Security</h2>
            <p class="text-slate-600 mb-4">We take reasonable measures to protect your information and ensure that your data is stored securely in our Aiven MySQL database.</p>
        </div>
    </section>
</body>
</html>
