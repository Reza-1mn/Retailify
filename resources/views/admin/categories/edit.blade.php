<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Perbarui Kategori</h1>
    </x-slot>

    @php
        $iconOptions = \App\Helpers\CategoryIconHelper::options();
        $initialIcon = old('icon') ?: \App\Helpers\CategoryIconHelper::resolve($category->icon, $category->name);
        $prodCount = $category->products_count ?? $category->products()->count();
    @endphp

    <div x-data="{
             categoryName: '{{ old('name', $category->name) }}',
             selectedIcon: '{{ $initialIcon }}',
             manualIconPicked: {{ old('icon') ? 'true' : (!empty($category->icon) && $category->icon !== 'auto' ? 'true' : 'false') }},
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
                        <span class="text-indigo-600 font-semibold">Edit</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Perbarui Kategori</h2>
                </div>
            </div>
        </div>

        <!-- Grid 12 Kolom -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Kolom Kiri: Form Edit Kategori -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

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
                               class="w-full px-4 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                               required>

                        @error('name')
                            <p class="text-rose-600 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilihan Ikon -->
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
                                    <p class="text-xs font-bold text-slate-800 truncate" x-text="categoryName || '{{ $category->name }}'"></p>
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
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Kolom Kanan: Info Kategori & Deteksi Ikon -->
            <div class="lg:col-span-5 space-y-5">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-extrabold text-slate-900">Informasi Kategori</h3>
                        <span class="px-2.5 py-1 rounded-md text-xs font-bold {{ $prodCount > 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $prodCount }} produk terkait
                        </span>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Nama Tersimpan</span>
                            <span class="font-bold text-slate-800">{{ $category->name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Icon Key Saat Ini</span>
                            <span class="font-mono font-semibold text-indigo-600">{{ $initialIcon }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-slate-500">Dibuat Pada</span>
                            <span class="font-medium text-slate-600">{{ optional($category->created_at)->format('d M Y H:i') ?? '-' }}</span>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-400 leading-relaxed pt-1">
                        Klik <strong class="text-slate-600">Otomatis Sesuai Nama</strong> jika ingin icon key disesuaikan kembali berdasarkan nama kategori saat disimpan.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>