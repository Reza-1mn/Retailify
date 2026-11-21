<x-app-layout>
    <div class="min-h-screen bg-slate-50/70 pb-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <a href="{{ route('admin.products.index') }}" class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 flex items-center justify-center shadow-sm transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <div>
                        <p class="text-xs font-semibold text-amber-600">Manage Products / Edit Product</p>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Perbarui Informasi Produk</h1>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    <!-- Kolom Kiri -->
                    <div class="lg:col-span-8 space-y-6">
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                            <h2 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3">Informasi Dasar Produk</h2>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Produk <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                                @error('name')<p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode SKU <span class="text-rose-500">*</span></label>
                                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                                           class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm font-mono focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                                    @error('sku')<p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori <span class="text-rose-500">*</span></label>
                                    <select name="category_id" class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                            <h2 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3">Harga &amp; Stok Inventori</h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Harga Beli / Modal (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="number" name="buy_price" value="{{ old('buy_price', $product->buy_price) }}" min="0"
                                           class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                                    @error('buy_price')<p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Harga Jual (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="number" name="sell_price" value="{{ old('sell_price', $product->sell_price) }}" min="0"
                                           class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                                    @error('sell_price')<p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Stok <span class="text-rose-500">*</span></label>
                                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                                           class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                                    @error('stock')<p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Batas Minimum Stok <span class="text-rose-500">*</span></label>
                                    <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0"
                                           class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                                    <p class="text-[11px] text-slate-400 mt-1">Peringatan stok menipis muncul jika stok &le; nilai ini.</p>
                                    @error('min_stock')<p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="lg:col-span-4 space-y-6">
                        <div x-data="{ photoPreview: '{{ $product->image ? asset('storage/' . $product->image) : '' }}' }" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                            <h2 class="text-sm font-extrabold text-slate-900">Foto Produk</h2>

                            <label class="block cursor-pointer">
                                <div class="w-full h-48 rounded-2xl border-2 border-dashed border-slate-300 hover:border-indigo-400 bg-slate-50/80 overflow-hidden flex flex-col items-center justify-center text-center p-4 transition">
                                    <template x-if="photoPreview">
                                        <img :src="photoPreview" class="w-full h-full object-cover rounded-xl" alt="Preview">
                                    </template>
                                    <template x-if="!photoPreview">
                                        <div class="space-y-2">
                                            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 mx-auto flex items-center justify-center">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <p class="text-xs font-bold text-indigo-600">Klik untuk ganti foto</p>
                                            <p class="text-[11px] text-slate-400">JPG, PNG, WEBP (Maks. 2MB)</p>
                                        </div>
                                    </template>
                                </div>
                                <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="hidden"
                                       @change="const file = $event.target.files[0]; if (file) { photoPreview = URL.createObjectURL(file); }">
                            </label>
                            <p class="text-[11px] text-slate-400 text-center">Klik kotak di atas untuk mengganti foto produk.</p>
                            @error('image')<p class="text-rose-600 text-xs font-medium">{{ $message }}</p>@enderror
                        </div>

                        <!-- Kartu Status Produk (Active / Inactive) -->
                        <div x-data="{ active: {{ $product->is_active ? 'true' : 'false' }} }" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-sm font-extrabold text-slate-900">Status Produk</h2>
                                    <p class="text-[11px] text-slate-400 mt-0.5" x-text="active ? 'Tampil di katalog Kasir (POS)' : 'Disembunyikan dari Kasir'"></p>
                                </div>
                                <input type="hidden" name="is_active" :value="active ? 1 : 0">
                                <button type="button" @click="active = !active"
                                        :class="active ? 'bg-emerald-500' : 'bg-slate-300'"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200">
                                    <span :class="active ? 'translate-x-5' : 'translate-x-0'"
                                          class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200"></span>
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2.5">
                            <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-sm transition">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="w-full py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-bold text-center transition">
                                Batal
                            </a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>