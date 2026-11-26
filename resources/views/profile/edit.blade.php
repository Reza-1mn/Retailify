<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Pengaturan Akun &amp; Sistem</h1>
            <span class="text-xs font-medium text-slate-400">Profil, Preferensi &amp; Kontrol</span>
        </div>
    </x-slot>

    @php
        $initialTab = session('status') === 'store-updated' ? 'toko' : 'profil';
        $adminStoreName = Auth::user()->store_name
            ?? \App\Models\User::where('role', 'admin')->whereNotNull('store_name')->value('store_name')
            ?? 'Retailify';
    @endphp

    <div class="w-full p-5 sm:p-6 lg:p-8 space-y-6 bg-slate-50/70 min-h-[calc(100vh-4rem)]"
         x-data="{
             activeTab: @js($initialTab),
             autoFocusPosSearch: true,
             qrisExactAmount: true
         }">

        <!-- Kartu Ringkasan Pengguna -->
        <div class="w-full rounded-2xl bg-white border border-slate-200/80 p-5 sm:p-6 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-extrabold text-base flex items-center justify-center shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900">{{ Auth::user()->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[11px] font-extrabold uppercase">
                            {{ Auth::user()->role ?? 'user' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <p class="text-xs text-slate-400">
                Bergabung sejak {{ optional(Auth::user()->created_at)->translatedFormat('d M Y') ?? '—' }}
            </p>
        </div>

        <!-- Navigasi Bagian Pengaturan -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <button type="button" @click="activeTab = 'profil'"
                    :class="activeTab === 'profil' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-2 rounded-xl border text-xs font-bold shrink-0 transition">
                Profil &amp; Keamanan
            </button>
            <button type="button" @click="activeTab = 'preferensi'"
                    :class="activeTab === 'preferensi' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-2 rounded-xl border text-xs font-bold shrink-0 transition">
                Preferensi Tampilan &amp; POS
            </button>
            @if ((Auth::user()->role ?? '') === 'admin')
                <button type="button" @click="activeTab = 'toko'"
                        :class="activeTab === 'toko' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        class="px-4 py-2 rounded-xl border text-xs font-bold shrink-0 transition">
                    Profil Toko &amp; Struk
                </button>
                <button type="button" @click="activeTab = 'kasir'"
                        :class="activeTab === 'kasir' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        class="px-4 py-2 rounded-xl border text-xs font-bold shrink-0 transition">
                    Manajemen Kasir
                </button>
            @endif
        </div>

        <!-- TAB 1: PROFIL & KEAMANAN -->
        <div x-show="activeTab === 'profil'" style="display: none;" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Kolom Kiri: Informasi Profil -->
                <div class="lg:col-span-6 rounded-2xl bg-white border border-slate-200/80 p-6 shadow-2xs space-y-5">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Informasi Profil</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Perbarui foto profil, nama tampilan, dan alamat email akun operasional kamu.</p>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('patch')

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 font-extrabold text-sm flex items-center justify-center border border-indigo-100">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="space-y-1">
                                <label class="inline-block px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 cursor-pointer transition">
                                    <span>Pilih Foto Profil</span>
                                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="hidden">
                                </label>
                                <p class="text-[11px] text-slate-400">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
                            </div>
                        </div>

                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input id="name" name="name" type="text" value="{{ old('name', Auth::user()->name) }}" required
                                   class="w-full rounded-xl border-slate-200 bg-slate-50/60 focus:bg-white focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                            @error('name')
                                <p class="text-rose-600 text-xs font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', Auth::user()->email) }}" required
                                   class="w-full rounded-xl border-slate-200 bg-slate-50/60 focus:bg-white focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                            @error('email')
                                <p class="text-rose-600 text-xs font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-1 flex items-center gap-3">
                            <button type="submit" class="btn-primary-theme px-4 py-2.5 rounded-xl text-xs font-extrabold">
                                Simpan Perubahan
                            </button>
                            @if (session('status') === 'profile-updated')
                                <span class="text-xs font-bold text-emerald-600">Tersimpan.</span>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Kolom Kanan: Perbarui Kata Sandi -->
                <div class="lg:col-span-6 rounded-2xl bg-white border border-slate-200/80 p-6 shadow-2xs space-y-5"
                     x-data="{ showPass: false }">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Perbarui Kata Sandi</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Gunakan kombinasi kata sandi yang kuat untuk menjaga keamanan akses akun.</p>
                        </div>
                        <button type="button" @click="showPass = !showPass"
                                class="text-xs font-bold text-indigo-600 hover:underline shrink-0"
                                x-text="showPass ? 'Sembunyikan' : 'Tampilkan'"></button>
                    </div>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                        @csrf
                        @method('put')

                        <div>
                            <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Saat Ini</label>
                            <input id="current_password" name="current_password" :type="showPass ? 'text' : 'password'" autocomplete="current-password"
                                   class="w-full rounded-xl border-slate-200 bg-slate-50/60 focus:bg-white focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru</label>
                            <input id="password" name="password" :type="showPass ? 'text' : 'password'" autocomplete="new-password" placeholder="Minimal 8 karakter"
                                   class="w-full rounded-xl border-slate-200 bg-slate-50/60 focus:bg-white focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                            <input id="password_confirmation" name="password_confirmation" :type="showPass ? 'text' : 'password'" autocomplete="new-password" placeholder="Ulangi kata sandi baru"
                                   class="w-full rounded-xl border-slate-200 bg-slate-50/60 focus:bg-white focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                        </div>

                        <div class="pt-1 flex items-center gap-3">
                            <button type="submit" class="btn-primary-theme px-4 py-2.5 rounded-xl text-xs font-extrabold">
                                Perbarui Kata Sandi
                            </button>
                            @if (session('status') === 'password-updated')
                                <span class="text-xs font-bold text-emerald-600">Kata sandi diperbarui.</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Zona Bahaya (Hanya Ditampilkan untuk Admin) -->
            @if ((Auth::user()->role ?? '') === 'admin')
                <div class="w-full max-w-2xl rounded-2xl bg-white border border-rose-200/80 p-6 shadow-2xs space-y-3">
                    <h3 class="text-sm font-extrabold text-rose-600">Zona Bahaya — Hapus Akun</h3>
                    <p class="text-xs text-slate-500">Setelah akun dihapus, seluruh akses login untuk akun ini akan dinonaktifkan secara permanen.</p>
                    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Yakin ingin menghapus akun ini secara permanen?');" class="pt-1">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn-danger-soft px-4 py-2 rounded-xl text-xs font-extrabold">
                            Hapus Akun Ini
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- TAB 2: PREFERENSI TAMPILAN & KASIR POS -->
        <div x-show="activeTab === 'preferensi'" style="display: none;" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-6 rounded-2xl bg-white border border-slate-200/80 p-6 shadow-2xs space-y-4">
                <h3 class="text-base font-extrabold text-slate-900">Preferensi Operasional Kasir</h3>
                <p class="text-xs text-slate-500">Sesuaikan perilaku input cepat saat melayani transaksi di halaman POS.</p>

                <label class="flex items-center justify-between gap-4 p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 cursor-pointer">
                    <div>
                        <p class="text-xs font-bold text-slate-800">Otomatis Isi Nominal Pas Saat Pilih QRIS</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Menyamakan nominal uang diterima dengan total tagihan QRIS.</p>
                    </div>
                    <input type="checkbox" x-model="qrisExactAmount" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                </label>

                <label class="flex items-center justify-between gap-4 p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 cursor-pointer">
                    <div>
                        <p class="text-xs font-bold text-slate-800">Fokus Kolom Pencarian / Kode Produk</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Memudahkan pencarian cepat nama produk atau SKU di layar kasir.</p>
                    </div>
                    <input type="checkbox" x-model="autoFocusPosSearch" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                </label>
            </div>

            <div class="lg:col-span-6 rounded-2xl bg-white border border-slate-200/80 p-6 shadow-2xs space-y-4">
                <h3 class="text-base font-extrabold text-slate-900">Keamanan &amp; Sesi Aktif</h3>
                <p class="text-xs text-slate-500">Pantau sesi login yang sedang aktif pada akun kamu saat ini.</p>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 flex items-center justify-between gap-4 text-xs">
                    <div>
                        <p class="font-bold text-slate-800">Sesi Browser Saat Ini</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Aktif sekarang &bull; IP {{ request()->ip() }}</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold">Aktif</span>
                </div>
            </div>
        </div>

        <!-- TAB 3: PROFIL TOKO & STRUK (KHUSUS ADMIN) -->
        @if ((Auth::user()->role ?? '') === 'admin')
            <div x-show="activeTab === 'toko'" style="display: none;" class="w-full max-w-3xl rounded-2xl bg-white border border-slate-200/80 p-6 shadow-2xs space-y-5">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Profil Toko &amp; Pengaturan Struk</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Informasi identitas toko yang tampil di bagian atas struk transaksi tanpa mengubah nama akun admin/kasir.</p>
                </div>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="update_section" value="store">
                    <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                    <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="store_name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Toko / Perusahaan</label>
                            <input id="store_name" name="store_name" type="text"
                                   value="{{ old('store_name', $adminStoreName) }}"
                                   placeholder="Contoh: Retailify Mart"
                                   class="w-full rounded-xl border-slate-200 bg-slate-50/60 focus:bg-white focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                            @error('store_name')
                                <p class="text-rose-600 text-xs font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="default_min_stock" class="block text-xs font-bold text-slate-700 mb-1.5">Batas Stok Minimum Default</label>
                            <input id="default_min_stock" type="number" value="5" min="0"
                                   class="w-full rounded-xl border-slate-200 bg-slate-50/60 text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="store_address" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat / Info Cabang (Struk)</label>
                        <input id="store_address" type="text" placeholder="Contoh: Jl. Jenderal Sudirman, Kota Jambi"
                               class="w-full rounded-xl border-slate-200 bg-slate-50/60 text-sm">
                    </div>

                    <div>
                        <label for="receipt_footer" class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Penutup Struk</label>
                        <input id="receipt_footer" type="text" value="Terima kasih telah berbelanja di {{ $adminStoreName }}."
                               class="w-full rounded-xl border-slate-200 bg-slate-50/60 text-sm">
                    </div>

                    <div class="pt-1 flex items-center gap-3">
                        <button type="submit" class="btn-primary-theme px-4 py-2.5 rounded-xl text-xs font-extrabold">
                            Simpan Profil Toko
                        </button>
                        @if (session('status') === 'store-updated')
                            <span class="text-xs font-bold text-emerald-600">Nama toko tersimpan.</span>
                        @endif
                    </div>
                </form>
            </div>

            <!-- TAB 4: MANAJEMEN AKUN KASIR (KHUSUS ADMIN) -->
            <div x-show="activeTab === 'kasir'" style="display: none;" class="w-full max-w-3xl rounded-2xl bg-white border border-slate-200/80 p-6 shadow-2xs space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Manajemen Akun Kasir</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Admin dapat menambahkan akun Kasir baru dan mengatur status akses operasional kasir.</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-bold">Akses Admin</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 text-xs text-slate-600 space-y-1.5">
                    <p class="font-bold text-slate-800">Kebijakan Akun Retailify:</p>
                    <p>&bull; Pendaftaran akun mandiri diperuntukkan bagi <strong>Admin</strong>.</p>
                    <p>&bull; Akun <strong>Kasir</strong> dibuat langsung oleh Admin agar kontrol akses toko tetap terpusat.</p>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>