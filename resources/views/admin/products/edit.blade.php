<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Perbarui Informasi Produk</h1>
    </x-slot>

    @php
        $existingImageUrl = !empty($product->image) ? asset('storage/' . $product->image) : null;
        $initialCost = old('buy_price', old('cost_price', $product->buy_price ?? $product->cost_price ?? 0));
        $initialSell = old('sell_price', $product->sell_price ?? 0);
        $initialMinStock = old('min_stock', $product->min_stock ?? 5);
        $selectedCategoryId = old('category_id', $product->category_id);
        $initialIsActive = (bool) old('is_active', $product->is_active ?? true);
    @endphp

    <div x-data="{
             costPrice: Number('{{ (float) $initialCost }}') || 0,
             sellPrice: Number('{{ (float) $initialSell }}') || 0,
             isActive: {{ $initialIsActive ? 'true' : 'false' }},
             existingImageUrl: @js($existingImageUrl),
             previewUrl: @js($existingImageUrl),
             get margin() {
                 return this.sellPrice - this.costPrice;
             },
             formatRp(val) {
                 return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
             },
             handleImage(event) {
                 const file = event.target.files[0];
                 if (!file) {
                     this.previewUrl = this.existingImageUrl;
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
                        <span class="text-slate-700 font-semibold">Edit Product</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Perbarui Informasi Produk</h2>
                </div>
            </div>

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                       value="{{ old('name', $product->name) }}"
                                       class="w-full px-4 py-2.5 rounded-xl bg-slate-50/60 border border-slate-200 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                                       required>
                                @error('name')
                                    <p class="text-rose-600 text-xs font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="sku" class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Kode SKU <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="sku"
                                           type="text"
                                           name="sku"
                                           value="{{ old('sku', $product->sku) }}"
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
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected((string) $selectedCategoryId === (string) $category->id)>
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
                                    Laba/Unit:
                                    <strong :class="margin >= 0 ? 'text-emerald-600' : 'text-rose-600'" x-text="formatRp(margin)"></strong>
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="buy_price" class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Harga Beli / Modal (Rp) <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="buy_price"
                                           type="number"
                                           name="buy_price"
                                           min="0"
                                           step="1"
                                           x-model.number="costPrice"
                                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/60 border border-slate-200 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                                           required>
                                    @error('buy_price')
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
                                        Stok <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="stock"
                                           type="number"
                                           name="stock"
                                           min="0"
                                           value="{{ old('stock', $product->stock) }}"
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
                                           value="{{ $initialMinStock }}"
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

                            <label class="h-44 rounded-2xl border border-dashed border-slate-300 bg-slate-50/40 hover:bg-slate-50 flex flex-col items-center justify-center cursor-pointer overflow-hidden transition">
                                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" @change="handleImage($event)" class="sr-only">
                                <template x-if="previewUrl">
                                    <img :src="previewUrl" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!previewUrl">
                                    <span class="text-xs font-bold text-slate-400">Klik untuk pilih foto</span>
                                </template>
                            </label>
                            <p class="text-[11px] text-slate-400 text-center">Klik kotak di atas untuk mengganti foto produk.</p>
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
                                Simpan Perubahan
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