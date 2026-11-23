<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Retailify — Solusi Point of Sale &amp; Inventori Modern</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @view-transition {
            navigation: auto;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .hero-gradient {
            background:
                radial-gradient(circle at 82% 22%, rgba(99, 102, 241, 0.45) 0%, transparent 45%),
                radial-gradient(circle at 15% 85%, rgba(124, 58, 237, 0.32) 0%, transparent 50%),
                linear-gradient(135deg, #1e1b4b 0%, #2e1065 52%, #1e1b4b 100%);
        }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .animate-float {
            animation: floatSlow 5s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: floatSlow 6s ease-in-out 1.5s infinite;
        }
    </style>
</head>
<body class="min-h-screen w-full bg-white text-slate-800 selection:bg-indigo-600 selection:text-white">
    <x-page-loader />

    <!-- HERO SECTION (Deep Indigo-Violet) -->
    <div class="hero-gradient w-full text-white relative overflow-hidden">

        <!-- Floating Pill Navbar -->
        <header class="pt-6 px-4 sm:px-8 lg:px-12 relative z-30">
            <nav class="w-full max-w-[1440px] mx-auto bg-white/10 backdrop-blur-md border border-white/15 rounded-full px-5 sm:px-7 py-3 flex items-center justify-between shadow-lg">
                <!-- Logo Komponen Retailify -->
                <a href="/" class="shrink-0">
                    <x-retailify-logo variant="light" />
                </a>

                <!-- Menu Tengah -->
                <div class="hidden md:flex items-center space-x-7 text-sm font-medium text-indigo-100">
                    <a href="#fitur" class="hover:text-white transition">Fitur Utama</a>
                    <a href="#modul" class="hover:text-white transition">Eksplorasi Modul</a>
                    <a href="#alur" class="hover:text-white transition">Alur Kerja</a>
                    <a href="#peran" class="hover:text-white transition">Peran &amp; Akses</a>
                    <a href="#faq" class="hover:text-white transition">FAQ</a>
                </div>

                <!-- Tombol Aksi Kanan -->
                <div class="flex items-center space-x-3 shrink-0">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="px-5 py-2 rounded-full bg-indigo-500 hover:bg-indigo-400 text-white text-xs sm:text-sm font-bold tracking-wide transition shadow-md">
                                BUKA DASHBOARD
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="hidden sm:inline-block text-sm font-semibold text-indigo-100 hover:text-white px-3 py-1.5 transition">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="px-5 py-2 rounded-full bg-indigo-500 hover:bg-indigo-400 text-white text-xs sm:text-sm font-bold tracking-wide transition shadow-md">
                                    DAFTAR ADMIN
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <!-- Hero Content -->
        <section class="w-full max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 pt-14 pb-24 lg:pt-20 lg:pb-28 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-20">

            <!-- Kolom Kiri: Headline & CTA -->
            <div class="lg:col-span-6 space-y-6">
                <div class="inline-flex items-center space-x-2.5 bg-white/10 border border-white/15 px-4 py-1.5 rounded-full text-xs sm:text-sm font-medium text-indigo-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Solusi POS &amp; Manajemen Stok Terpadu</span>
                </div>

                <h1 class="text-4xl sm:text-5xl xl:text-[56px] font-extrabold leading-[1.1] tracking-tight text-white">
                    Solusi Perangkat Lunak Kasir Modern
                </h1>

                <p class="text-indigo-200/90 text-base sm:text-lg leading-relaxed max-w-xl">
                    Sistem Point of Sale cerdas untuk mempercepat transaksi kasir, memotong stok inventori otomatis, dan memantau operasional toko secara real-time.
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ auth()->check() ? url('/pos') : (Route::has('register') ? route('register') : route('login')) }}"
                       class="inline-flex items-center space-x-2 px-7 py-3.5 rounded-full bg-white text-indigo-950 font-extrabold text-xs sm:text-sm tracking-wider uppercase hover:bg-indigo-50 transition shadow-xl">
                        <span>MULAI SEKARANG</span>
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10"/>
                        </svg>
                    </a>

                    <a href="#alur"
                       class="inline-flex items-center space-x-3 px-4 py-2.5 rounded-full text-sm font-semibold text-white hover:bg-white/10 transition">
                        <span class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </span>
                        <span>Lihat Cara Kerja</span>
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Floating UI Widgets -->
            <div class="lg:col-span-6 relative">
                <div class="relative mx-auto max-w-xl">

                    <!-- Card 1: Total Penjualan -->
                    <div class="animate-float bg-indigo-950/85 backdrop-blur-md border border-white/20 rounded-2xl p-4 w-56 shadow-2xl mb-4 sm:mb-0 sm:relative sm:z-20">
                        <p class="text-[11px] font-medium text-indigo-200">Total Penjualan</p>
                        <p class="text-2xl font-extrabold text-white mt-0.5">Rp 2.450K</p>
                        <div class="flex items-center space-x-1.5 text-[11px] text-emerald-300 mt-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>Hari Ini (38 Struk)</span>
                        </div>
                        <svg class="w-full h-10 mt-2 text-indigo-400" viewBox="0 0 180 40" fill="none">
                            <path d="M0 32 Q 30 10, 60 25 T 120 15 T 180 8" stroke="currentColor" stroke-width="2.5" fill="none"/>
                            <path d="M0 32 Q 30 10, 60 25 T 120 15 T 180 8 L 180 40 L 0 40 Z" fill="url(#chartGrad)" opacity="0.35"/>
                            <defs>
                                <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#818cf8"/>
                                    <stop offset="100%" stop-color="#1e1b4b" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>

                    <!-- Card 2: Quick Checkout -->
                    <div class="animate-float-delayed bg-indigo-400/20 backdrop-blur-md border border-white/25 rounded-2xl p-5 w-full sm:w-72 sm:ml-auto sm:-mt-16 shadow-2xl relative z-10">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-white">Quick Checkout</span>
                            <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full text-indigo-100">Tunai / QRIS</span>
                        </div>
                        <div class="bg-white/15 border border-white/20 rounded-xl p-3 mb-3">
                            <p class="text-[10px] text-indigo-200">Total Tagihan</p>
                            <p class="text-lg font-extrabold text-white">Rp 124.500</p>
                        </div>
                        <a href="{{ auth()->check() ? url('/pos') : route('login') }}"
                           class="block w-full text-center py-2.5 rounded-xl bg-white text-indigo-950 font-extrabold text-xs tracking-wider uppercase hover:bg-indigo-50 transition shadow">
                            PROSES TRANSAKSI
                        </a>
                    </div>

                    <!-- Card 3: Live Notifications -->
                    <div class="mt-4 sm:mt-4 sm:ml-24 space-y-2.5 relative z-20">
                        <div class="bg-white/15 backdrop-blur-md border border-white/20 rounded-xl px-3.5 py-2.5 flex items-center space-x-3 shadow-lg">
                            <div class="w-8 h-8 rounded-full bg-emerald-400 text-indigo-950 font-extrabold text-xs flex items-center justify-center shrink-0">
                                IN
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="inline-block bg-white text-indigo-950 text-[10px] font-extrabold px-2 py-0.5 rounded">+25 pcs</span>
                                <p class="text-xs text-white truncate mt-0.5">Restock Susu UHT Full Cream</p>
                            </div>
                        </div>

                        <div class="bg-white/15 backdrop-blur-md border border-white/20 rounded-xl px-3.5 py-2.5 flex items-center space-x-3 shadow-lg sm:translate-x-4">
                            <div class="w-8 h-8 rounded-full bg-sky-300 text-indigo-950 font-extrabold text-xs flex items-center justify-center shrink-0">
                                POS
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="inline-block bg-white text-indigo-950 text-[10px] font-extrabold px-2 py-0.5 rounded">Rp 42.000</span>
                                <p class="text-xs text-white truncate mt-0.5">INV-20261120 &bull; Kasir Utama</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </section>
    </div>

    <!-- TRUST BAR / JAMINAN OPERASIONAL -->
    <section class="w-full border-b border-slate-100 bg-slate-50/70 py-8">
        <div class="w-full max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <p class="text-xl sm:text-2xl font-extrabold text-indigo-600">Real-Time</p>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Pemotongan Stok Otomatis</p>
            </div>
            <div>
                <p class="text-xl sm:text-2xl font-extrabold text-slate-900">ACID Safe</p>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Penguncian Transaksi Database</p>
            </div>
            <div>
                <p class="text-xl sm:text-2xl font-extrabold text-slate-900">Multi-Role</p>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Akses Penuh Admin &amp; Kasir Terkelola</p>
            </div>
            <div>
                <p class="text-xl sm:text-2xl font-extrabold text-emerald-600">Audit Trail</p>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Log Keluar-Masuk Inventori</p>
            </div>
        </div>
    </section>

    <!-- SECTION 2: FITUR UTAMA -->
    <section id="fitur" class="py-20 w-full max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 scroll-mt-10">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="inline-block px-3.5 py-1 rounded-md bg-indigo-50 text-indigo-600 text-xs font-extrabold tracking-widest uppercase">
                RETAILIFY POS SOFTWARE
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 leading-tight">
                Tingkatkan Efisiensi Toko dengan Teknologi Terintegrasi
            </h2>
            <p class="text-slate-500 text-sm sm:text-base mt-3">
                Dirancang khusus untuk menyatukan pencatatan kasir dan kontrol stok gudang dalam satu sistem yang akurat.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-7 rounded-3xl bg-white border border-slate-200/80 hover:border-indigo-300 hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-extrabold text-lg mb-5">
                    01
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Transaksi Kasir Cepat</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                    Pencarian produk instan via nama atau SKU, kalkulasi otomatis total belanja dan kembalian, serta struk transaksi langsung siap pakai.
                </p>
            </div>

            <div class="p-7 rounded-3xl bg-white border border-slate-200/80 hover:border-indigo-300 hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-2xl bg-violet-600 text-white flex items-center justify-center font-extrabold text-lg mb-5">
                    02
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Sinkronisasi Stok Otomatis</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                    Setiap penjualan otomatis memotong stok produk dan mencatat riwayat keluar-masuk barang untuk mencegah selisih inventori.
                </p>
            </div>

            <div class="p-7 rounded-3xl bg-white border border-slate-200/80 hover:border-indigo-300 hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-2xl bg-indigo-950 text-white flex items-center justify-center font-extrabold text-lg mb-5">
                    03
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Manajemen Multi-Role</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                    Pendaftaran penuh untuk Admin serta pengelolaan akun Kasir terpusat agar hak akses dan operasional toko tetap terkendali.
                </p>
            </div>
        </div>
    </section>

    <!-- SECTION 3: EKSPLORASI MODUL INTERAKTIF (Sliding Pill Tabs) -->
    <section id="modul" class="w-full py-16 bg-slate-50 border-y border-slate-200/70 scroll-mt-10"
             x-data="{
                activeTab: 'pos',
                tabs: [
                    { id: 'pos', label: 'Kasir (POS)' },
                    { id: 'stok', label: 'Inventori & Restock' },
                    { id: 'akses', label: 'Role & Keamanan' }
                ],
                get activeIndex() {
                    return this.tabs.findIndex(t => t.id === this.activeTab);
                }
             }">
        <div class="w-full max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="text-center mb-8">
                <span class="inline-block px-3.5 py-1 rounded-md bg-indigo-100 text-indigo-700 text-xs font-extrabold tracking-widest uppercase">
                    EKSPLORASI MODUL
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                    Fitur Utama di Dalam Sistem
                </h2>
            </div>

            <!-- Sliding Pill Tabs -->
            <div class="flex justify-center mb-8">
                <div class="relative grid grid-cols-3 p-1.5 bg-slate-200/80 rounded-full w-full max-w-md">
                    <div class="absolute top-1.5 bottom-1.5 left-1.5 w-[calc(33.333%-4px)] bg-indigo-600 rounded-full shadow transition-transform duration-300 ease-out"
                         :style="`transform: translateX(${activeIndex * 100}%)`">
                    </div>
                    <template x-for="tab in tabs" :key="tab.id">
                        <button @click="activeTab = tab.id"
                                :class="activeTab === tab.id ? 'text-white' : 'text-slate-600 hover:text-slate-900'"
                                class="relative z-10 py-2 text-xs sm:text-sm font-bold text-center rounded-full transition-colors duration-200 focus:outline-none"
                                x-text="tab.label">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Card Konten -->
            <div class="max-w-5xl mx-auto bg-white rounded-3xl p-7 sm:p-10 border border-slate-200/70 shadow-sm min-h-[230px] flex items-center">
                <div x-show="activeTab === 'pos'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="w-full grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="space-y-2.5">
                        <h3 class="text-xl font-extrabold text-slate-900">Validasi Stok Real-Time Saat Checkout</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Sistem menggunakan penguncian transaksi database untuk memastikan jumlah pembelian tidak pernah melebihi stok yang tersedia.
                        </p>
                    </div>
                    <div class="bg-indigo-50/60 rounded-2xl p-5 border border-indigo-100 space-y-2 text-sm">
                        <div class="flex justify-between font-semibold"><span>Total Belanja</span><span class="text-indigo-700 font-extrabold">Rp 27.000</span></div>
                        <div class="flex justify-between text-slate-500"><span>Pembayaran</span><span>Rp 50.000</span></div>
                        <div class="pt-2 border-t border-indigo-200/60 flex justify-between font-extrabold text-emerald-600"><span>Kembalian</span><span>Rp 23.000</span></div>
                    </div>
                </div>

                <div x-show="activeTab === 'stok'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="display: none;"
                     class="w-full grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="space-y-2.5">
                        <h3 class="text-xl font-extrabold text-slate-900">Log Pergerakan Barang Masuk &amp; Keluar</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Setiap restock dari pemasok maupun penjualan kasir terekam otomatis di tabel riwayat stok beserta waktu dan nama operator.
                        </p>
                    </div>
                    <div class="bg-indigo-50/60 rounded-2xl p-5 border border-indigo-100 space-y-2 text-sm">
                        <div class="flex justify-between"><span>Penjualan POS</span><span class="font-bold text-rose-600">-2 pcs</span></div>
                        <div class="flex justify-between"><span>Restock Supplier</span><span class="font-bold text-emerald-600">+30 pcs</span></div>
                    </div>
                </div>

                <div x-show="activeTab === 'akses'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="display: none;"
                     class="w-full grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="space-y-2.5">
                        <h3 class="text-xl font-extrabold text-slate-900">Pemisahan Hak Akses Admin &amp; Kasir</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Middleware memproteksi rute pengelolaan master data, restock, dan manajemen akun kasir agar hanya dapat diakses oleh Admin.
                        </p>
                    </div>
                    <div class="bg-indigo-50/60 rounded-2xl p-5 border border-indigo-100 space-y-2 text-sm font-bold">
                        <div class="text-slate-800">Admin &rarr; Master Produk, Pemasok, Restock &amp; Akun Kasir</div>
                        <div class="text-indigo-700">Kasir &rarr; Halaman Transaksi POS</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: ALUR KERJA 3 LANGKAH -->
    <section id="alur" class="py-20 w-full max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 scroll-mt-10">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="inline-block px-3.5 py-1 rounded-md bg-indigo-50 text-indigo-600 text-xs font-extrabold tracking-widest uppercase">
                ALUR OPERASIONAL
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-3">Mulai dalam 3 Langkah Terstruktur</h2>
            <p class="text-slate-500 text-sm sm:text-base mt-2">
                Alur kerja terintegrasi mulai dari pendataan produk hingga pelaporan omzet harian.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="relative p-7 rounded-3xl bg-slate-50 border border-slate-200/70">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-indigo-600 text-white font-extrabold text-xs mb-4">1</span>
                <h3 class="text-base font-extrabold text-slate-900">Kelola Master Produk &amp; Kasir</h3>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                    Admin menginput kategori, SKU produk, harga modal, harga jual, batas stok minimum, serta akun kasir operasional.
                </p>
            </div>

            <div class="relative p-7 rounded-3xl bg-slate-50 border border-slate-200/70">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-indigo-600 text-white font-extrabold text-xs mb-4">2</span>
                <h3 class="text-base font-extrabold text-slate-900">Proses Transaksi di POS</h3>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                    Kasir memilih item belanja pelanggan, memilih metode pembayaran, dan menyelesaikan transaksi dengan perhitungan kembalian otomatis.
                </p>
            </div>

            <div class="relative p-7 rounded-3xl bg-slate-50 border border-slate-200/70">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-indigo-600 text-white font-extrabold text-xs mb-4">3</span>
                <h3 class="text-base font-extrabold text-slate-900">Pantau Stok &amp; Laporan</h3>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                    Stok berkurang secara real-time, peringatan restock muncul otomatis, dan grafik omzet harian langsung tersaji di Dashboard.
                </p>
            </div>
        </div>
    </section>

    <!-- SECTION 4B: PERBANDINGAN OPERASIONAL & SKEMA PERAN -->
    <section id="peran" class="w-full py-20 bg-slate-50 border-y border-slate-200/70 scroll-mt-10">
        <div class="w-full max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 space-y-12">
            <div class="text-center max-w-2xl mx-auto">
                <span class="inline-block px-3.5 py-1 rounded-md bg-indigo-100 text-indigo-700 text-xs font-extrabold tracking-widest uppercase">
                    EFISIENSI &amp; HAK AKSES
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                    Lebih Tertata Dibanding Pencatatan Manual
                </h2>
                <p class="text-slate-500 text-sm sm:text-base mt-2">
                    Menghindarkan selisih stok akibat catatan terpisah sekaligus membatasi akses perubahan data sensitif.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Kolom Perbandingan -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="rounded-3xl bg-white p-6 border border-slate-200/80 space-y-3">
                        <span class="inline-block px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-[11px] font-extrabold uppercase">Pencatatan Manual</span>
                        <h3 class="text-base font-extrabold text-slate-900">Rawan Selisih &amp; Lambat</h3>
                        <ul class="space-y-2.5 text-xs sm:text-sm text-slate-600 pt-1">
                            <li>&bull; Hitung total dan kembalian manual saat antrean ramai.</li>
                            <li>&bull; Stok baru dicek setelah barang habis di rak.</li>
                            <li>&bull; Riwayat restock dan penjualan sulit ditelusuri balik.</li>
                        </ul>
                    </div>

                    <div class="rounded-3xl bg-white p-6 border-2 border-indigo-600/30 shadow-sm space-y-3">
                        <span class="inline-block px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 text-[11px] font-extrabold uppercase">Dengan Retailify</span>
                        <h3 class="text-base font-extrabold text-slate-900">Terukur &amp; Tersinkronisasi</h3>
                        <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700 pt-1">
                            <li>&bull; Kalkulasi otomatis, input ribuan rapi, dan struk siap cetak.</li>
                            <li>&bull; Stok berkurang otomatis saat checkout dan terpantau batas minimum.</li>
                            <li>&bull; Log stok serta riwayat transaksi tercatat rapi per invoice.</li>
                        </ul>
                    </div>
                </div>

                <!-- Kolom Skema Peran Admin & Kasir -->
                <div class="lg:col-span-5 rounded-3xl bg-white p-6 sm:p-7 border border-slate-200/80 flex flex-col justify-between space-y-5">
                    <div class="space-y-4">
                        <h3 class="text-lg font-extrabold text-slate-900">Aturan Hak Akses Akun</h3>
                        <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 space-y-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-indigo-700">Admin (Akses Penuh)</p>
                            <p class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                                Memiliki akses penuh ke Dashboard, Master Produk, Kategori, Supplier, Restock, Laporan, serta penambahan dan pengelolaan akun Kasir.
                            </p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-700">Kasir (Operasional POS)</p>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Akun ditambahkan langsung oleh Admin untuk menjalankan transaksi di halaman Point of Sale secara fokus dan aman.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4C: FAQ SINGKAT -->
    <section id="faq" class="py-20 w-full max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 scroll-mt-10"
             x-data="{ openFaq: 1 }">
        <div class="max-w-3xl mx-auto space-y-8">
            <div class="text-center">
                <span class="inline-block px-3.5 py-1 rounded-md bg-indigo-50 text-indigo-600 text-xs font-extrabold tracking-widest uppercase">
                    PERTANYAAN UMUM
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                    Hal yang Sering Ditanyakan
                </h2>
            </div>

            <div class="space-y-3">
                <div class="rounded-2xl border border-slate-200/80 bg-white overflow-hidden">
                    <button type="button" @click="openFaq = openFaq === 1 ? null : 1"
                            class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 text-sm font-extrabold text-slate-900">
                        <span>Bagaimana alur pendaftaran akun Admin dan Kasir?</span>
                        <span class="text-indigo-600 text-lg leading-none" x-text="openFaq === 1 ? '&minus;' : '+'"></span>
                    </button>
                    <div x-show="openFaq === 1" class="px-6 pb-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Pendaftaran akun penuh diperuntukkan bagi Admin, sedangkan akun Kasir dibuat dan dikelola langsung oleh Admin agar kontrol akses toko tetap terjaga.
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-white overflow-hidden">
                    <button type="button" @click="openFaq = openFaq === 2 ? null : 2"
                            class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 text-sm font-extrabold text-slate-900">
                        <span>Apakah stok produk langsung berkurang setelah kasir checkout?</span>
                        <span class="text-indigo-600 text-lg leading-none" x-text="openFaq === 2 ? '&minus;' : '+'"></span>
                    </button>
                    <div x-show="openFaq === 2" style="display: none;" class="px-6 pb-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Ya. Setiap transaksi yang berhasil diproses akan memotong stok produk secara otomatis dan mencatat mutasinya ke dalam log inventori.
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-white overflow-hidden">
                    <button type="button" @click="openFaq = openFaq === 3 ? null : 3"
                            class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 text-sm font-extrabold text-slate-900">
                        <span>Metode pembayaran apa saja yang tersedia di kasir POS?</span>
                        <span class="text-indigo-600 text-lg leading-none" x-text="openFaq === 3 ? '&minus;' : '+'"></span>
                    </button>
                    <div x-show="openFaq === 3" style="display: none;" class="px-6 pb-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Kasir mendukung pembayaran Tunai (dengan hitung kembalian otomatis) dan QRIS, lengkap dengan tampilan struk transaksi yang bisa dicetak.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 5: CLOSING CTA BANNER -->
    <section class="pb-20 px-4 sm:px-8 lg:px-12 w-full max-w-[1440px] mx-auto">
        <div class="hero-gradient rounded-3xl p-8 sm:p-14 text-center text-white shadow-2xl relative overflow-hidden">
            <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight max-w-2xl mx-auto leading-tight">
                Siap Mengoptimalkan Operasional Kasir &amp; Inventori Toko Anda?
            </h2>
            <p class="text-indigo-200 text-sm sm:text-base max-w-xl mx-auto mt-4">
                Masuk menggunakan akun Admin atau Kasir untuk mulai mencoba simulasi Point of Sale dan sinkronisasi stok Retailify.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center space-x-2 px-8 py-3.5 rounded-full bg-white text-indigo-950 font-extrabold text-xs sm:text-sm tracking-wider uppercase hover:bg-indigo-50 transition shadow-lg">
                    <span>MASUK KE APLIKASI</span>
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10"/>
                    </svg>
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-7 py-3.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs sm:text-sm tracking-wider uppercase transition">
                        DAFTAR AKUN ADMIN
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- FOOTER PROFESIONAL -->
    <footer class="w-full bg-slate-50 border-t border-slate-200/80 pt-12 pb-8">
        <div class="w-full max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-8 border-b border-slate-200/80">
                <div class="md:col-span-5 space-y-3">
                    <x-retailify-logo variant="dark" />
                    <p class="text-xs text-slate-500 leading-relaxed max-w-sm">
                        Sistem Point of Sale (POS) dan Manajemen Inventori terintegrasi yang dirancang untuk mempercepat transaksi ritel dan menjaga akurasi stok gudang.
                    </p>
                </div>

                <div class="md:col-span-3 space-y-2.5 text-xs">
                    <p class="font-extrabold text-slate-900 uppercase tracking-wider">Navigasi</p>
                    <ul class="space-y-2 text-slate-500">
                        <li><a href="#fitur" class="hover:text-indigo-600 transition">Fitur Utama</a></li>
                        <li><a href="#modul" class="hover:text-indigo-600 transition">Eksplorasi Modul</a></li>
                        <li><a href="#alur" class="hover:text-indigo-600 transition">Alur Operasional</a></li>
                        <li><a href="#peran" class="hover:text-indigo-600 transition">Peran &amp; Akses</a></li>
                        <li><a href="#faq" class="hover:text-indigo-600 transition">FAQ</a></li>
                    </ul>
                </div>

                <div class="md:col-span-4 space-y-2.5 text-xs">
                    <p class="font-extrabold text-slate-900 uppercase tracking-wider">Akses Sistem</p>
                    <ul class="space-y-2 text-slate-500">
                        <li><a href="{{ route('login') }}" class="hover:text-indigo-600 transition">Masuk Kasir / Admin</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}" class="hover:text-indigo-600 transition">Registrasi Akun Admin</a></li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
                <p>&copy; 2026 <strong>Retailify</strong>. Hak Cipta Dilindungi.</p>
                <p>Proyek Rekayasa Perangkat Lunak &mdash; Smart POS &amp; Inventory System</p>
            </div>
        </div>
    </footer>

</body>
</html>