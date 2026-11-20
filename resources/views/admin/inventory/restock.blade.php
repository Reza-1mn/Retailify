<x-app-layout>
    <div class="min-h-screen bg-slate-50/70 pb-12">
        <div class="bg-white border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center shadow-md shadow-amber-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-0.5">
                                <span>Manajemen Toko</span>
                                <span>/</span>
                                <span class="text-amber-600">Inventori</span>
                            </div>
                            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Restock &amp; Penyesuaian Stok</h1>
                        </div>
                    </div>

                    <a href="{{ route('admin.inventory.logs') }}"
                       class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm shadow-indigo-200 transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Lihat Riwayat Stok
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-6">
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm">
                    <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3l9 16H3L12 3z"/></svg>
                        </span>
                        <ul class="text-sm font-semibold list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Produk</p>
                        <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $products->count() }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Supplier Aktif</p>
                        <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $suppliers->count() }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border {{ $lowStockProducts->isNotEmpty() ? 'border-amber-200 bg-amber-50/30' : 'border-slate-200/80' }} p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider {{ $lowStockProducts->isNotEmpty() ? 'text-amber-600' : 'text-slate-400' }}">Perlu Restock</p>
                        <p class="text-3xl font-extrabold {{ $lowStockProducts->isNotEmpty() ? 'text-amber-600' : 'text-emerald-600' }} mt-1">{{ $lowStockProducts->count() }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl {{ $lowStockProducts->isNotEmpty() ? 'bg-amber-100 text-amber-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
            </div>

            @if($lowStockProducts->isNotEmpty())
                <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <p class="text-sm font-extrabold text-amber-900">Peringatan Stok Menipis</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($lowStockProducts as $item)
                            <span class="px-3 py-1.5 text-xs font-semibold bg-white text-amber-800 border border-amber-200 rounded-lg">
                                {{ $item->sku }} - {{ $item->name }} (Sisa {{ $item->stock }} / Min {{ $item->min_stock }})
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">Barang Masuk</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Tambah stok dari supplier</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.inventory.restock.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Produk</label>
                            <select name="product_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                        [{{ $product->sku }}] {{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Supplier <span class="text-slate-400 font-medium">(Opsional)</span></label>
                            <select name="supplier_id" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white">
                                <option value="">-- Tanpa Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Jumlah Masuk (Qty)</label>
                            <input type="number" name="qty" min="1" value="{{ old('qty') }}" required placeholder="Contoh: 20"
                                   class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Catatan <span class="text-slate-400 font-medium">(Opsional)</span></label>
                            <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Contoh: Pengiriman rutin mingguan"
                                   class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white">
                        </div>
                        <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-indigo-200 transition">
                            Simpan Barang Masuk
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m8-8l-8 8 8 8"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">Penyesuaian Stok Keluar</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Catat barang rusak, hilang, atau kedaluwarsa</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.inventory.adjustment.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Produk</label>
                            <select name="product_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                        [{{ $product->sku }}] {{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Jumlah Dikurangi (Qty)</label>
                            <input type="number" name="qty" min="1" value="{{ old('qty') }}" required placeholder="Contoh: 2"
                                   class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Alasan Penyesuaian</label>
                            <input type="text" name="notes" value="{{ old('notes') }}" required placeholder="Contoh: Kemasan penyok / kedaluwarsa"
                                   class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white">
                        </div>
                        <div class="pt-16 sm:pt-[68px]">
                            <button type="submit" class="w-full py-3 px-4 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-sm shadow-amber-200 transition">
                                Catat Penyesuaian Stok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
