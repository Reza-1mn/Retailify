<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Retailify') }} — Sistem Kasir &amp; Inventori</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Anti-FOUC: Terapkan Night Mode & Warna Tema sebelum halaman dirender -->
    <script>
        (function() {
            const theme = localStorage.getItem('retailify_theme') || 'light';
            const accent = localStorage.getItem('retailify_accent') || 'indigo';
            if (theme === 'dark') document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-accent', accent);
        })();
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Panggil CSS Modular di bawah Tailwind CDN agar override selalu menang -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/themes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ui.css') }}">

</head>
<body class="bg-slate-50 text-slate-800 antialiased transition-colors duration-200"
      x-data="{
          sidebarOpen: false,
          sidebarDesktop: localStorage.getItem('retailify_sidebar') !== 'hidden',
          darkMode: localStorage.getItem('retailify_theme') === 'dark',
          accentColor: localStorage.getItem('retailify_accent') || 'indigo',
          themeMenuOpen: false,
          accents: [
                { id: 'indigo',   name: 'Soft Indigo',    hex: '#6366f1' },
                { id: 'sage',     name: 'Sage Emerald',   hex: '#49876b' },
                { id: 'lavender', name: 'Dusty Lavender', hex: '#8665cf' },
                { id: 'ocean',    name: 'Soft Ocean',     hex: '#4587b3' },
                { id: 'rose',     name: 'Muted Rose',     hex: '#c45c76' }
            ],
          toggleDesktopSidebar() {
              this.sidebarDesktop = !this.sidebarDesktop;
              localStorage.setItem('retailify_sidebar', this.sidebarDesktop ? 'visible' : 'hidden');
          },
          toggleDarkMode(event) {
              const applyTheme = () => {
                  this.darkMode = !this.darkMode;
                  document.documentElement.classList.toggle('dark', this.darkMode);
                  localStorage.setItem('retailify_theme', this.darkMode ? 'dark' : 'light');
              };

              // Gunakan View Transitions API untuk animasi lingkaran menyebar dari tombol
              if (document.startViewTransition && event && event.clientX) {
                  const x = event.clientX;
                  const y = event.clientY;
                  const endRadius = Math.hypot(
                      Math.max(x, window.innerWidth - x),
                      Math.max(y, window.innerHeight - y)
                  );

                  const transition = document.startViewTransition(() => {
                      applyTheme();
                  });

                  transition.ready.then(() => {
                      document.documentElement.animate(
                          {
                              clipPath: [
                                  `circle(0px at ${x}px ${y}px)`,
                                  `circle(${endRadius}px at ${x}px ${y}px)`
                              ]
                          },
                          {
                              duration: 450,
                              easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
                              pseudoElement: '::view-transition-new(root)'
                          }
                      );
                  });
              } else {
                  // Fallback animasi fade halus
                  document.body.classList.add('theme-animating');
                  applyTheme();
                  setTimeout(() => document.body.classList.remove('theme-animating'), 350);
              }
          },
          setAccent(id) {
              this.accentColor = id;
              document.documentElement.setAttribute('data-accent', id);
              localStorage.setItem('retailify_accent', id);
          }
      }">
    <x-page-loader />

    <div class="min-h-screen lg:h-screen lg:overflow-hidden flex bg-slate-50">
        
        <!-- Overlay Gelap untuk Layar Mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" style="display: none;"
             class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden transition-opacity"></div>

        <!-- SIDEBAR KIRI -->
        <aside :class="[
                   sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                   sidebarDesktop ? 'lg:w-60 lg:translate-x-0' : 'lg:w-0 lg:overflow-hidden lg:border-r-0'
               ]"
               class="app-sidebar fixed inset-y-0 left-0 z-40 w-60 bg-white border-r border-slate-200/80 flex flex-col justify-between transition-all duration-200 ease-in-out lg:static lg:h-screen shrink-0">
            
            <div class="w-60">
               <!-- Logo Brand + Tombol Hide Sidebar -->
                <div class="h-16 px-5 flex items-center justify-between border-b border-slate-100">
                    <a href="{{ route('dashboard') }}">
                        <x-retailify-logo />
                    </a>

                    <button @click="toggleDesktopSidebar()" title="Sembunyikan Sidebar"
                            class="hidden lg:inline-flex p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                        </svg>
                    </button>

                    <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Menu Navigasi Utama -->
                <nav class="p-4 space-y-1.5">
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('pos.index') }}"
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition {{ request()->routeIs('pos.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span>Kasir (POS)</span>
                    </a>

                    @if(Auth::user()->role === 'admin')
                        <div class="pt-4 pb-1 px-3.5">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Manajemen Toko</p>
                        </div>

                        <a href="{{ route('admin.products.index') }}"
                           class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Master Produk</span>
                        </a>

                        <a href="{{ route('admin.categories.index') }}"
                           class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>Kategori Produk</span>
                        </a>

                        <a href="{{ route('admin.suppliers.index') }}"
                           class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition {{ request()->routeIs('admin.suppliers.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Data Supplier</span>
                        </a>

                        <a href="{{ route('admin.inventory.restock') }}"
                           class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition {{ request()->routeIs('admin.inventory.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <span>Restock &amp; Log Stok</span>
                        </a>

                        <a href="{{ route('reports.index') }}"
                           class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition {{ request()->routeIs('reports.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Laporan &amp; Analisis</span>
                        </a>
                    @endif
                </nav>
            </div>

            <!-- Menu Bawah Sidebar -->
            <div class="w-60 p-4 border-t border-slate-100 space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Pengaturan Akun</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- KONTEN UTAMA KANAN -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Bar Header (Tambahkan relative z-30) -->
            <header class="app-topbar relative z-30 h-16 bg-white border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between shrink-0">
                <div class="flex items-center space-x-3">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition" title="Buka Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <button @click="toggleDesktopSidebar()"
                            :title="sidebarDesktop ? 'Sembunyikan Sidebar' : 'Tampilkan Sidebar'"
                            class="hidden lg:inline-flex p-2 rounded-xl border border-slate-200/80 text-slate-600 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50/50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <path d="M9 3v18"/>
                        </svg>
                    </button>

                    @isset($header)
                        {{ $header }}
                    @endisset
                </div>

                <!-- KONTROL KANAN: Night Mode, Customizer Warna, dan Profil User -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    
                    <!-- Tombol Night Mode (Bulan / Matahari) -->
                    <button @click="toggleDarkMode($event)"
                            :title="darkMode ? 'Beralih ke Light Mode' : 'Beralih ke Night Mode'"
                            class="p-2 rounded-xl border border-slate-200/80 text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition active:scale-95">
                        <!-- Ikon Bulan (Saat Light Mode) -->
                        <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <!-- Ikon Matahari (Saat Night Mode) -->
                        <svg x-show="darkMode" style="display: none;" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    <!-- Tombol & Dropdown Pemilih Warna Tema -->
                    <div class="relative" @click.outside="themeMenuOpen = false">
                        <button @click="themeMenuOpen = !themeMenuOpen" title="Ubah Warna Tema"
                                class="p-2 rounded-xl border border-slate-200/80 text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition flex items-center space-x-1.5">
                            <span class="w-3.5 h-3.5 rounded-full bg-indigo-600 shadow-xs"></span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Popover Pilihan Warna -->
                        <div x-show="themeMenuOpen" style="display: none;" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-200/80 p-3 z-[999] space-y-2.5">
                            <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 px-1">Warna Aksentuasi</p>
                            <div class="grid grid-cols-5 gap-1.5 px-1">
                                <template x-for="item in accents" :key="item.id">
                                    <button type="button" @click="setAccent(item.id)" :title="item.name"
                                            :style="`background-color: ${item.hex}`"
                                            :class="accentColor === item.id ? 'ring-2 ring-offset-2 ring-slate-400 scale-110' : 'opacity-80 hover:opacity-100'"
                                            class="w-6 h-6 rounded-full transition transform">
                                    </button>
                                </template>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between px-1">
                                <span class="text-xs font-semibold text-slate-600">Night Mode</span>
                                <button type="button" @click="toggleDarkMode($event)"
                                        :class="darkMode ? 'bg-indigo-600' : 'bg-slate-200'"
                                        class="w-9 h-5 rounded-full relative transition-colors">
                                    <span :class="darkMode ? 'translate-x-4' : 'translate-x-0.5'"
                                        class="block w-4 h-4 rounded-full bg-white shadow transition-transform"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Info User Pojok Kanan Atas (Edit Profil) -->
                    <a href="{{ route('profile.edit') }}" title="Edit Profil Akun"
                       class="flex items-center space-x-3 group p-1.5 -mr-1.5 rounded-xl hover:bg-slate-100/80 transition">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 leading-none transition">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] font-semibold text-indigo-600 uppercase mt-1">{{ Auth::user()->role }}</p>
                        </div>
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                 class="w-9 h-9 rounded-full object-cover ring-2 ring-indigo-600/20 group-hover:ring-indigo-600 transition shadow-sm">
                        @else
                            <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-extrabold text-sm flex items-center justify-center shadow-sm transition">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </a>
                </div>
            </header>

            <!-- Isi Halaman -->
            <main class="app-main flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Prefetch Link saat Hover -->
    <script>
        document.addEventListener('mouseover', (e) => {
            const link = e.target.closest('a');
            if (!link || !link.href || link.target || link.dataset.prefetched) return;
            if (link.origin === window.location.origin && !link.href.includes('#') && !link.href.includes('export') && !link.href.includes('print')) {
                const prefetch = document.createElement('link');
                prefetch.rel = 'prefetch';
                prefetch.href = link.href;
                document.head.appendChild(prefetch);
                link.dataset.prefetched = 'true';
            }
        });
    </script>
</body>
</html>