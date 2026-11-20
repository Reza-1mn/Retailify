<x-app-layout>
    <div class="min-h-screen bg-slate-50/70 pb-12">
        <div class="bg-white border-b border-slate-200/80">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center gap-4">
                <a href="{{ route('admin.products.index') }}" class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 flex items-center justify-center transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <p class="text-xs font-semibold text-amber-600">Edit Data</p>
                    <h1 class="text-xl font-extrabold text-slate-900">Perbarui Informasi Produk</h1>
                </div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kode SKU <span class="text-rose-500">*</span></label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm font-mono focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                            @error('sku')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kategori <span class="text-rose-500">*</span></label>
                            <select name="category_id" class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Produk <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                        @error('name')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Beli / Modal (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="buy_price" value="{{ old('buy_price', $product->buy_price) }}" min="0"
                                   class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                            @error('buy_price')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Jual (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="sell_price" value="{{ old('sell_price', $product->sell_price) }}" min="0"
                                   class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                            @error('sell_price')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Stok <span class="text-rose-500">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                                   class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                            @error('stock')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Batas Minimum Stok <span class="text-rose-500">*</span></label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0"
                                   class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                            <p class="text-xs text-slate-400 mt-1.5">Notifikasi otomatis aktif saat stok &le; nilai ini.</p>
                            @error('min_stock')<p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm shadow-indigo-200 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>