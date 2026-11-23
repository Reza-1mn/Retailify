<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Tambah Produk</h1>
    </x-slot>

    @php
        $nextNumber = str_pad((\App\Models\Product::max('id') ?? 0) + 1, 3, '0', STR_PAD_LEFT);
        $defaultSku = 'PRD-' . $nextNumber;
    @endphp

    <div x-data="{
             sku: '{{ old('sku', $defaultSku) }}',
             costPrice: Number('{{ old('cost_price', 0) }}') || 0,
             sellPrice: Number('{{ old('sell_price', 0) }}') || 0,
             isActive: {{ old('is_active', 1) ? 'true' : 'false' }},
             previewUrl: null,
             get margin() {
                 return this.sellPrice - this.costPrice;
             },
             formatRp(val) {
                 return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
             },
             handleImage(event) {
                 const file = event.target.files[0];
                 if (!file) {
                     this.previewUrl = null;
                     return;
                 }
                 this.previewUrl = URL.createObjectURL(file);
             }
         }"
         class="min-h-screen bg-slate-50/70 pb-12">

        <div class="w-full px-4 sm:px-6 lg:px-8 pt-6 space-y-6">

            <!-- Header Halaman -->
            <div class="flex items-center gap-3.5">
                <a href="{{ route('admin.products.index') }}"
                   class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 flex items-center justify-center transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                        <a href="{{ route('admin.products.index') }}" class="hover:text-indigo-600 transition">Manage Products</a>
                        <span>/</span>
                        <span class="text-indigo-600 font-semibold">Add Product</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Tambah Produk Baru</h2>
                </div>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                    <!-- KOLOM KIRI (8 Kolom): Informasi Dasar + Harga & Stok -->
                    <div class="lg:col-span-8 space-y-6">

                        <!-- Informasi Dasar Produk -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                            <h3 class="text-sm font-extrabold text-slate-900">Informasi Dasar Produk</h3>

                            <div>
                                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nama Produk <span class="text-rose-500">*</span>
                                </label>
                                <input id="name"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="Contoh: Susu UHT Full Cream 250ml"
                                       class="w-full px-4 py-2.5 rounded-xl bg-slate-50/60 border border-slate-200 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                                       required
                                       autofocus>
                                @error('name')
                                    <p class="text-rose-600 text-xs font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label for="sku" class="block text-xs font-bold text-slate-700">
                                            Kode SKU <span class="text-rose-500">*</span>
                                        </label>
                                        <button type="button"
                                                @click="sku = '{{ $defaultSku }}'"
                                                class="text-[11px] font-bold text-indigo-600 hover:underline">
                                            Gunakan {{ $defaultSku }}
                                        </button>
                                    </div>
                                    <input id="sku"
                                           type="text"
                                           name="sku"
                                           x-model="sku"
                                           placeholder="Contoh: PRD-001"
                                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/60 border border-slate-200 text-sm font-mono text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                                           required>
                                    @error('sku')
                                        <p class="text-rose-600 text-xs font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="category_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Kategori <span class="text-rose-500">*</span>
                                    </label>
                                    <select id="category_id"
                                            name="category_id"
                                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50/60 border border-slate-200 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                                            required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-rose-600 text-xs font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Harga & Stok Inventori -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-sm font-extrabold text-slate-900">Harga &amp; Stok Inventori</h3>
                                <span class="text-xs font-semibold text-slate-500">
                                    Estimasi Laba/Unit:
                                    <strong :class="margin >= 0 ? 'text-emerald-600' : 'text-rose-600'" x-text="formatRp(margin)"></strong>
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="cost_price" class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Harga Beli / Modal (Rp) <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="cost_price"
                                           type="number"
                                           name="cost_price"
                                           min="0"
                                           step="1"
                                           x-model.number="costPrice"
                                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/60 border border-slate-200 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                                           required>
                                    @error('cost_price')
                                        <p class="text-rose-600 text-xs font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="sell_price" class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Harga Jual (Rp) <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="sell_price"
                                           type="number"
                                           name="sell_price"
                                           min="0"
                                           step="1"
                                           x-model.number="sellPrice"
                                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/60 border border-slate-200 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                                           required>
                                    @error('sell_price')
                                        <p class="text-rose-600 text-xs font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="stock" class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Stok Awal <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="stock"
                                           type="number"
                                           name="stock"
                                           min="0"
                                           value="{{ old('stock', 0) }}"
                                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/60 border border-slate-200 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                                           required>
                                    @error('stock')
                                        <p class="text-rose-600 text-xs font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="min_stock" class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Batas Minimum Stok <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="min_stock"
                                           type="number"
                                           name="min_stock"
                                           min="0"
                                           value="{{ old('min_stock', 5) }}"
                                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/60 border border-slate-200 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                                           required>
                                    <p class="text-[11px] text-slate-400 mt-1">Peringatan stok menipis muncul jika stok &le; nilai ini.</p>
                                    @error('min_stock')
                                        <p class="text-rose-600 text-xs font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- KOLOM KANAN (4 Kolom): Foto Produk + Status + Tombol Aksi -->
                    <div class="lg:col-span-4 space-y-5">

                        <!-- Foto Produk -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-3">
                            <h3 class="text-sm font-extrabold text-slate-900">Foto Produk</h3>

                            <label class="h-48 rounded-2xl border border-dashed border-slate-300 bg-slate-50/40 hover:bg-slate-50 flex flex-col items-center justify-center cursor-pointer overflow-hidden transition relative">
                                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" @change="handleImage($event)" class="sr-only">

                                <template x-if="previewUrl">
                                    <img :src="previewUrl" alt="Preview Produk" class="w-full h-full object-cover">
                                </template>

                                <template x-if="!previewUrl">
                                    <div class="text-center px-4">
                                        <svg class="w-7 h-7 text-indigo-500 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="3"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
                                        </svg>
                                        <p class="text-xs font-bold text-indigo-600">Klik untuk pilih foto</p>
                                        <p class="text-[11px] text-slate-400 mt-1">JPG, PNG, WEBP (Maks. 2MB)</p>
                                    </div>
                                </template>
                            </label>
                            @error('image')
                                <p class="text-rose-600 text-xs font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Produk -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-extrabold text-slate-900">Status Produk</p>
                                <p class="text-xs text-slate-400 mt-0.5">Tampil di katalog Kasir (POS)</p>
                            </div>
                            <input type="hidden" name="is_active" :value="isActive ? 1 : 0">
                            <button type="button"
                                    @click="isActive = !isActive"
                                    :class="isActive ? 'bg-indigo-600' : 'bg-slate-300'"
                                    class="w-12 h-7 rounded-full p-1 transition flex items-center shrink-0">
                                <span :class="isActive ? 'translate-x-5' : 'translate-x-0'"
                                      class="w-5 h-5 rounded-full bg-white shadow-2xs transition-transform"></span>
                            </button>
                        </div>

                        <!-- Tombol Simpan & Batal -->
                        <div class="space-y-2.5 pt-1">
                            <button type="submit"
                                    class="btn-primary-theme w-full py-3 px-4 rounded-xl text-sm font-bold shadow-xs">
                                Simpan Produk
                            </button>
                            <a href="{{ route('admin.products.index') }}"
                               class="w-full py-2.5 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-bold flex items-center justify-center transition">
                                Batal
                            </a>
                        </div>

                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>