<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Retailify — Solusi Point of Sale &amp; Inventori Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .hero-gradient {
            background: radial-gradient(circle at 85% 20%, #4f46e5 0%, #2e1065 48%, #1e1b4b 100%);
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
<body class="bg-white text-slate-800 selection:bg-indigo-600 selection:text-white">
    <x-page-loader />
    <!-- Hero Wrapper dengan Gradasi Deep Indigo-Violet -->
    <div class="hero-gradient text-white relative overflow-hidden">
        
        <!-- Floating Pill Navbar (Sesuai Referensi) -->
        <header class="pt-6 px-4 sm:px-6 relative z-30">
            <nav class="max-w-6xl mx-auto bg-white/10 border border-white/15 rounded-full px-5 sm:px-7 py-3 flex items-center justify-between shadow-lg">
                <!-- Logo Brand -->
                <a href="/">
                    <x-retailify-logo variant="light" />
                </a>

                <!-- Menu Tengah -->
                <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-indigo-100">
                    <a href="#fitur" class="hover:text-white transition">Fitur Utama</a>
                    <a href="#modul" class="hover:text-white transition">Modul POS</a>
                    <a href="#alur" class="hover:text-white transition">Cara Kerja</a>
                </div>

                <!-- Tombol Aksi Kanan (Pill Button) -->
                <div class="flex items-center space-x-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/pos') }}"
                               class="px-5 py-2 rounded-full bg-indigo-500 hover:bg-indigo-400 text-white text-xs sm:text-sm font-bold tracking-wide transition shadow-md">
                                BUKA KASIR (POS)
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="hidden sm:inline-block text-sm font-semibold text-indigo-100 hover:text-white px-3 py-1.5 transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                               class="px-5 py-2 rounded-full bg-indigo-500 hover:bg-indigo-400 text-white text-xs sm:text-sm font-bold tracking-wide transition shadow-md">
                                DAFTAR GRATIS
                            </a>
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <!-- Hero Content -->
        <section class="max-w-6xl mx-auto px-4 sm:px-6 pt-14 pb-24 lg:pt-20 lg:pb-28 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-20">
            
            <!-- Kolom Kiri: Headline & CTA -->
            <div class="lg:col-span-6 space-y-6">
                <!-- Pill Badge -->
                <div class="inline-flex items-center space-x-2.5 bg-white/10 border border-white/15 px-4 py-1.5 rounded-full text-xs sm:text-sm font-medium text-indigo-100">
                    <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                    <span>Solusi POS &amp; Manajemen Stok Terpadu</span>
                </div>

                <!-- Judul Utama -->
                <h1 class="text-4xl sm:text-5xl lg:text-[52px] font-extrabold leading-[1.12] tracking-tight text-white">
                    Solusi Perangkat Lunak Kasir Modern
                </h1>

                <!-- Deskripsi -->
                <p class="text-indigo-200 text-base sm:text-lg leading-relaxed max-w-lg">
                    Sistem Point of Sale cerdas untuk mempercepat transaksi kasir, memotong stok inventori otomatis, dan memantau operasional toko secara real-time.
                </p>

                <!-- CTA Buttons (Putih Bulat + Tombol Sekunder) -->
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center space-x-2 px-7 py-3.5 rounded-full bg-white text-indigo-950 font-extrabold text-xs sm:text-sm tracking-wider uppercase hover:bg-indigo-50 transition shadow-xl">
                        <span>MULAI SEKARANG</span>
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10"/>
                        </svg>
                    </a>

                    <a href="#modul"
                       class="inline-flex items-center space-x-3 px-4 py-2.5 rounded-full text-sm font-semibold text-white hover:bg-white/10 transition">
                        <span class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </span>
                        <span>Lihat Demo Alur</span>
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Floating UI Cards (Adaptasi Persis Susunan Referensi) -->
            <div class="lg:col-span-6 relative">
                <div class="relative mx-auto max-w-lg">
                    
                    <!-- Card 1 (Kiri Atas): Profit / Penjualan Hari Ini + Grafik -->
                    <div class="animate-float bg-indigo-950/80 border border-white/20 rounded-2xl p-4 w-56 shadow-2xl mb-4 sm:mb-0 sm:relative sm:z-20">
                        <p class="text-[11px] font-medium text-indigo-200">Total Penjualan</p>
                        <p class="text-2xl font-extrabold text-white mt-0.5">Rp 2.450K</p>
                        <div class="flex items-center space-x-1.5 text-[11px] text-emerald-300 mt-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>Hari Ini (38 Struk)</span>
                        </div>
                        <!-- Mini SVG Wave Chart -->
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

                    <!-- Card 2 (Kanan Tengah): Quick Checkout Widget -->
                    <div class="animate-float-delayed bg-indigo-400/25 border border-white/25 rounded-2xl p-5 w-full sm:w-72 sm:ml-auto sm:-mt-16 shadow-2xl relative z-10">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-white">Quick Checkout</span>
                            <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full text-indigo-100">Tunai / QRIS</span>
                        </div>
                        <div class="bg-white/15 border border-white/20 rounded-xl p-3 mb-3">
                            <p class="text-[10px] text-indigo-200">Total Tagihan</p>
                            <p class="text-lg font-extrabold text-white">Rp 124.500</p>
                        </div>
                        <a href="{{ route('login') }}"
                           class="block w-full text-center py-2.5 rounded-xl bg-white text-indigo-950 font-extrabold text-xs tracking-wider uppercase hover:bg-indigo-50 transition shadow">
                            PROSES TRANSAKSI
                        </a>
                    </div>

                    <!-- Card 3 (Kanan Bawah): Stacked Live Stock / Transaction Notifications -->
                    <div class="mt-4 sm:mt-4 sm:ml-24 space-y-2.5 relative z-20">
                        <div class="bg-white/15 border border-white/20 rounded-xl px-3.5 py-2.5 flex items-center space-x-3 shadow-lg">
                            <div class="w-8 h-8 rounded-full bg-emerald-400 text-indigo-950 font-extrabold text-xs flex items-center justify-center shrink-0">
                                IN
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="inline-block bg-white text-indigo-950 text-[10px] font-extrabold px-2 py-0.5 rounded">+25 pcs</span>
                                <p class="text-xs text-white truncate mt-0.5">Restock Susu UHT Full Cream</p>
                            </div>
                        </div>

                        <div class="bg-white/15 border border-white/20 rounded-xl px-3.5 py-2.5 flex items-center space-x-3 shadow-lg sm:translate-x-4">
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

    <!-- Section 2: Fitur Utama (Sesuai Bagian Bawah Referensi dengan Badge Pill) -->
    <section id="fitur" class="py-20 max-w-6xl mx-auto px-4 sm:px-6 scroll-mt-10">
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
            <div class="p-7 rounded-3xl bg-slate-50 border border-slate-100 hover:border-indigo-200 hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-extrabold text-lg mb-5">
                    01
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Transaksi Kasir Cepat</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                    Pencarian produk instan via nama atau SKU, kalkulasi otomatis total belanja dan kembalian, serta penerbitan nomor invoice unik.
                </p>
            </div>

            <div class="p-7 rounded-3xl bg-slate-50 border border-slate-100 hover:border-indigo-200 hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-2xl bg-violet-600 text-white flex items-center justify-center font-extrabold text-lg mb-5">
                    02
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Sinkronisasi Stok Otomatis</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                    Setiap penjualan otomatis memotong stok produk dan mencatat riwayat keluar-masuk barang untuk mencegah selisih inventori.
                </p>
            </div>

            <div class="p-7 rounded-3xl bg-slate-50 border border-slate-100 hover:border-indigo-200 hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-extrabold text-lg mb-5">
                    03
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Manajemen Multi-Role</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                    Pembatasan hak akses antara Admin (kelola produk, pemasok, restock) dan Kasir (operasional transaksi harian).
                </p>
            </div>
        </div>
    </section>

    <!-- Section 3: Modul Interaktif dengan Sliding Tab -->
    <section id="modul" class="py-16 bg-slate-50 border-y border-slate-100"
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
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-8">
                <span class="inline-block px-3.5 py-1 rounded-md bg-indigo-100 text-indigo-700 text-xs font-extrabold tracking-widest uppercase">
                    EKSPLORASI MODUL
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                    Bagaimana Retailify Bekerja?
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
            <div class="bg-white rounded-3xl p-7 sm:p-10 border border-slate-200/70 shadow-sm min-h-[230px] flex items-center">
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
                            Middleware memproteksi rute pengelolaan master data dan harga modal agar hanya dapat diakses oleh akun berstatus Admin.
                        </p>
                    </div>
                    <div class="bg-indigo-50/60 rounded-2xl p-5 border border-indigo-100 space-y-2 text-sm font-bold">
                        <div class="text-slate-800">Admin &rarr; Master Produk, Pemasok, Restock</div>
                        <div class="text-indigo-700">Kasir &rarr; Halaman Transaksi POS</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="alur" class="bg-white py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between text-xs sm:text-sm text-slate-500 gap-4">
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 rounded-full bg-indigo-600 inline-block"></span>
                <span class="font-extrabold text-slate-900">Retailify</span>
                <span>&copy; 2026 Proyek Rekayasa Perangkat Lunak.</span>
            </div>
            <div>
                Point of Sale &amp; Sistem Inventori Terintegrasi
            </div>
        </div>
    </footer>

</body>
</html>