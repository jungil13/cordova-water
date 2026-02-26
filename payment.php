<?php
require_once __DIR__ . '/includes/auth.php';
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Guide - Cordova Water System Inc.</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans']},colors:{primary:{DEFAULT:'#0A4D68',dark:'#083A4D'},accent:'#00D9FF'}}}}</script>
    <style>
        .step-card{transition:all 0.3s ease}
        .step-card.active{border-color:#0A4D68;background-color:#f0f9ff;transform:translateY(-4px)}
        @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide{animation: slideIn 0.4s ease forwards;}
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">
<?php include __DIR__ . '/includes/header.php'; ?>

<section class="pt-32 pb-16 bg-gradient-to-br from-primary via-primary-dark to-slate-900 relative h-[400px] flex items-center">
    <div class="absolute inset-0 bg-[url('images/background.jpg')] bg-cover bg-center opacity-25"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
    <div class="relative max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4">Payment Guide</h1>
        <p class="text-lg text-white/90">A step-by-step guide to paying your water bill securely.</p>
    </div>
</section>

<section class="py-12 -mt-24 relative z-10 mb-24">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 space-y-4">
                <button onclick="switchTab('gcash')" id="btn-gcash" class="w-full p-6 text-left rounded-2xl bg-white border-2 border-slate-100 shadow-xl flex items-center gap-4 transition-all hover:border-primary/50 group active">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center p-2 group-hover:bg-blue-100">
                        <img src="images/gcash.jpg" class="w-full h-full object-contain" alt="GCash" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/GCash_logo.svg/2560px-GCash_logo.svg.png'">
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">GCash</h3>
                        <p class="text-xs text-slate-500">Fast & Mobile Friendly</p>
                    </div>
                </button>
                <button onclick="switchTab('palawan')" id="btn-palawan" class="w-full p-6 text-left rounded-2xl bg-white border-2 border-slate-100 shadow-xl flex items-center gap-4 transition-all hover:border-primary/50 group">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center p-2 group-hover:bg-orange-100">
                        <img src="images/palawan.png" class="w-full h-full object-contain" alt="Palawan" onerror="this.src='https://www.palawanpawnshop.com/assets/images/logo.png'">
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Palawan Express</h3>
                        <p class="text-xs text-slate-500">In-Store & Mobile App</p>
                    </div>
                </button>
            </div>

            <div class="lg:col-span-2">
                <div id="content-gcash" class="animate-slide">
                    <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10 border border-slate-100">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 text-sm flex items-center justify-center">1</span>
                                Pay with GCash
                            </h2>
                        </div>
                        <div class="grid md:grid-cols-2 gap-10">
                            <div class="space-y-8">
                                <div class="flex gap-4">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center shrink-0">1</div>
                                    <div><p class="font-bold text-slate-900">Scan QR Code</p><p class="text-sm text-slate-500 mt-1">Open your GCash app, tap "Scan QR" and scan our official QR code.</p></div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center shrink-0">2</div>
                                    <div><p class="font-bold text-slate-900">Enter Amount</p><p class="text-sm text-slate-500 mt-1">Input the total amount due from your latest bill.</p></div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center shrink-0">3</div>
                                    <div><p class="font-bold text-slate-900">Save Screenshot</p><p class="text-sm text-slate-500 mt-1">IMPORTANT: Take a clear screenshot of the successful transaction.</p></div>
                                </div>
                            </div>
                            <div class="bg-slate-50 rounded-2xl p-6 flex flex-col items-center justify-center border-2 border-dashed border-slate-200">
                                <img src="images/gcash.jpg" class="w-full max-w-[200px] border-4 border-white shadow-lg rounded-xl" alt="QR Code" onerror="this.src='https://via.placeholder.com/200?text=GCash+QR'">
                                <p class="text-center mt-6 text-sm text-slate-600 font-bold">Cordova Water System Inc.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="content-palawan" class="hidden animate-slide">
                    <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10 border border-slate-100">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 text-sm flex items-center justify-center">1</span>
                                Pay with Palawan
                            </h2>
                        </div>
                        <div class="grid md:grid-cols-2 gap-10">
                            <div class="space-y-8">
                                <div class="flex gap-4">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center shrink-0">1</div>
                                    <div><p class="font-bold text-slate-900">Over-the-Counter</p><p class="text-sm text-slate-500 mt-1">Visit any Palawan branch and fill out the Send Money form.</p></div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center shrink-0">2</div>
                                    <div><p class="font-bold text-slate-900">Input Details</p><p class="text-sm text-slate-500 mt-1">Use 'Cordova Water System Inc.' as the recipient.</p></div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center shrink-0">3</div>
                                    <div><p class="font-bold text-slate-900">Reference Number</p><p class="text-sm text-slate-500 mt-1">Keep the transaction slip for your record and dashboard upload.</p></div>
                                </div>
                            </div>
                            <div class="bg-slate-50 rounded-2xl p-6 flex flex-col items-center justify-center border-2 border-dashed border-slate-200">
                                <img src="images/palawan.png" class="w-full max-w-[200px] border-4 border-white shadow-lg rounded-xl" alt="QR Code" onerror="this.src='https://via.placeholder.com/200?text=Palawan+QR'">
                                <p class="text-center mt-6 text-sm text-slate-600 font-bold">Merchant: CWS-INC-001</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function switchTab(tab) {
    document.getElementById('content-gcash').classList.add('hidden');
    document.getElementById('content-palawan').classList.add('hidden');
    document.getElementById('btn-gcash').classList.remove('active', 'border-primary');
    document.getElementById('btn-palawan').classList.remove('active', 'border-primary');
    
    document.getElementById('content-' + tab).classList.remove('hidden');
    document.getElementById('btn-' + tab).classList.add('active', 'border-primary');
}
</script>
</body>
</html>
