<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Tambah Kategori</h1>
    </x-slot>

    @php
        $iconOptions = \App\Helpers\CategoryIconHelper::options();
    @endphp

    <div x-data="{
             categoryName: '{{ old('name', '') }}',
             selectedIcon: '{{ old('icon', 'auto') }}',
             manualIconPicked: {{ old('icon') && old('icon') !== 'auto' ? 'true' : 'false' }},
             presets: [
                 'Minuman Dingin',
                 'Makanan Ringan',
                 'Sembako',
                 'Perlengkapan Mandi',
                 'Roti & Bakery',
                 'Alat Tulis'
             ],
             applyPreset(name) {
                 this.categoryName = name;
                 this.manualIconPicked = false;
                 this.detectIcon(name);
             },
             detectIcon(val) {
                if (this.manualIconPicked) return;
                const lower = (val || '').toLowerCase();
                if (/wajah|muka|kulit|skincare|serum|toner|moisturizer|sunscreen|facial|lip|kosmetik|kecantikan/.test(lower)) this.selectedIcon = 'skincare';
                else if (/rambut|hair|shampo|shampoo|conditioner|kondisioner|hair tonic|hair vitamin|pomade|sisir/.test(lower)) this.selectedIcon = 'hair';
                else if (/sehat|kesehatan|obat|apotek|vitamin|suplemen|masker|plester|minyak kayu putih|p3k|herbal/.test(lower)) this.selectedIcon = 'health';
                else if (/bayi|balita|anak|popok|diaper|dot|telon/.test(lower)) this.selectedIcon = 'baby';
                else if (/minum|kopi|teh|air|jus|soda|es\s/.test(lower)) this.selectedIcon = 'coffee';
                else if (/susu|keju|yogurt|mentega|olahan/.test(lower)) this.selectedIcon = 'milk';
                else if (/roti|kue|bakery|pastry|donat|bolu/.test(lower)) this.selectedIcon = 'bread';
                else if (/snack|ringan|keripik|biskuit|coklat|wafer|permen/.test(lower)) this.selectedIcon = 'cookie';
                else if (/sembako|beras|pokok|gula|minyak|tepung|telur|garam/.test(lower)) this.selectedIcon = 'rice';
                else if (/bumbu|masak|sambal|pedas|cabai|cabe|rokok|korek/.test(lower)) this.selectedIcon = 'flame';
                else if (/mandi|bersih|sabun|deterjen|pewangi|cuci|tisu|lantai/.test(lower)) this.selectedIcon = 'sparkles';
                else if (/rumah|tangga|perabot|dapur|lampu/.test(lower)) this.selectedIcon = 'home';
                else if (/tulis|atk|buku|kertas|pulpen|pensil/.test(lower)) this.selectedIcon = 'pencil';
                else this.selectedIcon = 'tag';
            }
         }"
         x-init="detectIcon(categoryName)"
         class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <a href="{{ route('admin.categories.index') }}"
                   class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 flex items-center justify-center transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                        <span>Manajemen Toko</span>
                        <span>/</span>
                        <a href="{{ route('admin.categories.index') }}" class="hover:text-indigo-600 transition">Manage Categories</a>
                        <span>/</span>
                        <span class="text-indigo-600 font-semibold">Tambah</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Tambah Kategori Baru</h2>
                </div>
            </div>
        </div>

        <!-- Grid 12 Kolom -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Kolom Kiri: Form Tambah Kategori -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Nama Kategori -->
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <label for="category_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Nama Kategori <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400">Unik (maks. 100 karakter)</span>
                        </div>

                        <input id="category_name"
                               type="text"
                               name="name"
                               x-model="categoryName"
                               @input="detectIcon($event.target.value)"
                               placeholder="Contoh: Minuman Dingin, Makanan Ringan, Sembako"
                               class="w-full px-4 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                               required
                               autofocus>

                        <!-- Contoh Cepat -->
                        <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                            <span class="text-[11px] font-semibold text-slate-400 mr-1">Isi cepat:</span>
                            <template x-for="preset in presets" :key="preset">
                                <button type="button"
                                        @click="applyPreset(preset)"
                                        class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-[11px] font-semibold text-slate-600 transition"
                                        x-text="preset"></button>
                            </template>
                        </div>

                        @error('name')
                            <p class="text-rose-600 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilihan Ikon (Dengan Ikon Asli) -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Ikon Kategori
                            </label>
                            <button type="button"
                                    @click="manualIconPicked = false; detectIcon(categoryName)"
                                    class="text-xs font-bold text-indigo-600 hover:underline">
                                Otomatis Sesuai Nama
                            </button>
                        </div>

                        <input type="hidden" name="icon" :value="manualIconPicked ? selectedIcon : 'auto'">

                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                            @foreach($iconOptions as $key => $label)
                                <button type="button"
                                        @click="selectedIcon = '{{ $key }}'; manualIconPicked = true"
                                        :class="selectedIcon === '{{ $key }}'
                                            ? 'border-indigo-600 bg-indigo-50/80 text-indigo-700 ring-1 ring-indigo-600/30'
                                            : 'border-slate-200 bg-slate-50/50 text-slate-600 hover:bg-slate-50'"
                                        class="p-2.5 rounded-xl border text-left transition flex items-start gap-2.5">
                                    <span :class="selectedIcon === '{{ $key }}' ? 'bg-indigo-600 text-white' : 'bg-white border border-slate-200 text-indigo-600'"
                                          class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 mt-0.5 transition">
                                        <x-category-icon :icon="$key" class="w-3.5 h-3.5" />
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold leading-snug truncate">{{ $label }}</p>
                                        <p class="text-[10px] font-mono text-slate-400 truncate">{{ $key }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        <!-- Preview Ikon & Kategori -->
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                    @foreach($iconOptions as $key => $label)
                                        <span x-show="selectedIcon === '{{ $key }}'" class="flex items-center justify-center">
                                            <x-category-icon :icon="$key" class="w-5 h-5" />
                                        </span>
                                    @endforeach
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate" x-text="categoryName || 'Nama kategori baru'"></p>
                                    <p class="text-[11px] text-slate-500">
                                        Mode: <span class="font-semibold text-slate-700" x-text="manualIconPicked ? 'Manual' : 'Otomatis'"></span>
                                        &bull; Key: <span class="font-mono text-indigo-600" x-text="selectedIcon"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        @error('icon')
                            <p class="text-rose-600 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.categories.index') }}"
                           class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-bold transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="btn-primary-theme px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold shadow-xs">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>

            <!-- Kolom Kanan: Panduan Deteksi Ikon Otomatis -->
            <div class="lg:col-span-5 space-y-5">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 16v-4m0-4h.01"/>
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Deteksi Ikon Otomatis</h3>
                            <p class="text-[11px] text-slate-400">Dikelola oleh <code class="font-mono">CategoryIconHelper</code></p>
                        </div>
                    </div>

                    <p class="text-xs text-slate-500 leading-relaxed">
                        Kamu tidak perlu mengunggah file SVG. Saat nama kategori diketik, sistem mencocokkan kata kunci umum secara otomatis:
                    </p>

                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-600">Minuman, Kopi, Teh, Jus</span>
                            <span class="font-mono text-[11px] font-semibold text-indigo-600">coffee</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-600">Susu, Keju, Yogurt, Olahan</span>
                            <span class="font-mono text-[11px] font-semibold text-indigo-600">milk</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-600">Roti, Kue, Bakery</span>
                            <span class="font-mono text-[11px] font-semibold text-indigo-600">bread</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-600">Snack, Ringan, Keripik, Biskuit</span>
                            <span class="font-mono text-[11px] font-semibold text-indigo-600">cookie</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-600">Sembako, Beras, Gula, Minyak</span>
                            <span class="font-mono text-[11px] font-semibold text-indigo-600">rice</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-600">Bumbu, Masak, Pedas, Rokok</span>
                            <span class="font-mono text-[11px] font-semibold text-indigo-600">flame</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-slate-600">Mandi, Bersih, Sabun, Rumah, ATK</span>
                            <span class="font-mono text-[11px] font-semibold text-indigo-600">sparkles / home / pencil</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>