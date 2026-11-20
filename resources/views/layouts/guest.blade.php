<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Retailify') }}</title>
  

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/themes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    <style>
        @view-transition {
            navigation: auto;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .brand-panel-gradient {
            background: radial-gradient(circle at 85% 20%, #4f46e5 0%, #2e1065 55%, #1e1b4b 100%);
        }
        @keyframes formEnter {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-form-enter {
            animation: formEnter 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <x-page-loader />
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 p-3 sm:p-4 bg-slate-50">
        
        <!-- SISI KIRI: Logo Retailify & Form Autentikasi -->
        <div class="bg-white rounded-3xl lg:rounded-r-none flex flex-col justify-between p-6 sm:p-10 lg:p-14">
            <!-- Header Logo -->
            <div>
                <a href="/">
                    <x-retailify-logo variant="dark" />
                </a>
            </div>

            <!-- Wadah Form Utama -->
            <div class="w-full max-w-md mx-auto my-6 animate-form-enter">
                {{ $slot }}
            </div>

            <!-- Footer Kiri -->
            <div class="flex items-center justify-between text-xs text-slate-400">
                <span>&copy; 2026 Retailify POS</span>
                <a href="/" class="hover:text-indigo-600 font-medium transition">&larr; Kembali ke Beranda</a>
            </div>
        </div>

        <!-- SISI KANAN: Panel Brand Deep Indigo-Violet -->
        <div class="hidden lg:flex brand-panel-gradient rounded-3xl text-white p-12 flex-col justify-center relative overflow-hidden"
             style="view-transition-name: auth-brand-panel;">
            <div class="max-w-lg mx-auto w-full space-y-8 relative z-10">
                <div class="space-y-3">
                    <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight tracking-tight">
                        Kelola transaksi kasir dan stok toko dalam satu layar.
                    </h2>
                    <p class="text-indigo-200 text-sm leading-relaxed">
                        Masuk menggunakan akun Admin atau Kasir untuk memproses penjualan, memantau sisa stok, dan mencatat barang masuk secara real-time.
                    </p>
                </div>

                <!-- Mockup Dashboard Preview Card -->
                <div class="bg-white text-slate-800 rounded-2xl p-5 shadow-2xl border border-white/15 space-y-4">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-indigo-600 text-white p-3.5 rounded-xl">
                            <p class="text-[10px] text-indigo-200 font-medium">Total Penjualan</p>
                            <p class="text-base font-extrabold mt-1">Rp 2.450K</p>
                            <span class="inline-block mt-1.5 text-[9px] bg-white/20 px-1.5 py-0.5 rounded font-bold">+14% Hari Ini</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-100 p-3.5 rounded-xl">
                            <p class="text-[10px] text-slate-400 font-medium">Total Transaksi</p>
                            <p class="text-base font-extrabold text-slate-900 mt-1">38 Struk</p>
                            <svg class="w-full h-5 mt-1 text-indigo-500" viewBox="0 0 100 25" fill="none">
                                <path d="M0 20 Q 25 5, 50 15 T 100 4" stroke="currentColor" stroke-width="2" fill="none"/>
                            </svg>
                        </div>
                        <div class="bg-slate-50 border border-slate-100 p-3.5 rounded-xl">
                            <p class="text-[10px] text-slate-400 font-medium">Stok Terjual</p>
                            <p class="text-base font-extrabold text-slate-900 mt-1">142 Unit</p>
                            <span class="inline-block mt-1.5 text-[9px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-bold">Tersinkron</span>
                        </div>
                    </div>

                    <div class="border border-slate-100 rounded-xl overflow-hidden">
                        <div class="bg-slate-50 px-3.5 py-2 border-b border-slate-100 flex justify-between items-center">
                            <span class="text-[11px] font-bold text-slate-700">Transaksi Kasir Terbaru</span>
                            <span class="text-[10px] text-indigo-600 font-semibold">Live POS</span>
                        </div>
                        <div class="divide-y divide-slate-100 text-xs">
                            <div class="px-3.5 py-2.5 flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-slate-800">INV-20261120-A81K</p>
                                    <p class="text-[10px] text-slate-400">Susu UHT Full Cream (x2)</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-slate-900">Rp 13.000</p>
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Lunas</span>
                                </div>
                            </div>
                            <div class="px-3.5 py-2.5 flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-slate-800">INV-20261120-B19P</p>
                                    <p class="text-[10px] text-slate-400">Roti Tawar Kupas (x1)</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-slate-900">Rp 14.000</p>
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Lunas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>