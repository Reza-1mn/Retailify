<x-app-layout>
    <div x-data="{ search: '', onlyLowStock: false }" class="min-h-screen bg-slate-50/70 pb-12">
        <!-- Top Banner Header -->
        <div class="bg-white border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center shadow-md shadow-emerald-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-0.5">
                                <span>Master Data</span>
                                <span>/</span>
                                <span class="text-emerald-600">Produk</span>
                            </div>
                            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Katalog & Stok Produk</h1>
                        </div>
                    </div>

                    <a href="{{ route('admin.products.create') }}"
                       class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm shadow-indigo-200 transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Tambah Produk
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

            @php
                $lowStockCount = $products->filter(fn($p) => $p->stock <= $p->min_stock)->count();
                $totalStockUnits = $products->sum('stock');
            @endphp

            <!-- Kartu Ringkasan -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total SKU Produk</p>
                        <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $products->count() }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Unit Tersedia</p>
                        <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalStockUnits, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border {{ $lowStockCount > 0 ? 'border-rose-200 bg-rose-50/30' : 'border-slate-200/80' }} p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider {{ $lowStockCount > 0 ? 'text-rose-500' : 'text-slate-400' }}">Perlu Restock</p>
                        <p class="text-3xl font-extrabold {{ $lowStockCount > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-1">{{ $lowStockCount }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl {{ $lowStockCount > 0 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Bar Pencarian & Filter Stok -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                    <input type="text" x-model="search" placeholder="Cari SKU, nama produk, atau kategori..."
                           class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition">
                </div>

                <button type="button" @click="onlyLowStock = !onlyLowStock"
                        :class="onlyLowStock ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 border rounded-xl text-xs font-bold transition shrink-0">
                    <span class="w-2 h-2 rounded-full" :class="onlyLowStock ? 'bg-white' : 'bg-rose-500'"></span>
                    Filter Stok Menipis ({{ $lowStockCount }})
                </button>
            </div>

            <!-- Tabel Produk -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200/80 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-6">SKU</th>
                                <th class="py-4 px-6">Produk & Kategori</th>
                                <th class="py-4 px-6">Harga Beli</th>
                                <th class="py-4 px-6">Harga Jual</th>
                                <th class="py-4 px-6">Stok & Status</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($products as $product)
                                @php $isLow = $product->stock <= $product->min_stock; @endphp
                                <tr x-show="(search === '' || '{{ strtolower($product->sku . ' ' . $product->name . ' ' . ($product->category->name ?? '')) }}'.includes(search.toLowerCase())) && (!onlyLowStock || {{ $isLow ? 'true' : 'false' }})"
                                    class="hover:bg-slate-50/90 transition-colors">
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg font-mono text-xs font-bold">{{ $product->sku }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <p class="font-bold text-slate-800">{{ $product->name }}</p>
                                        <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700">
                                            {{ $product->category->name ?? 'Tanpa Kategori' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-slate-500">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-800">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2.5">
                                            <span class="text-base font-extrabold {{ $isLow ? 'text-rose-600' : 'text-slate-800' }}">{{ $product->stock }}</span>
                                            @if($isLow)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs bg-rose-100 text-rose-700 rounded-full font-bold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-ping"></span>
                                                    Stok Menipis
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs bg-emerald-50 text-emerald-700 rounded-full font-semibold">
                                                    Aman
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Min. stok: {{ $product->min_stock }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-amber-50 text-slate-700 hover:text-amber-700 rounded-lg text-xs font-semibold transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk {{ $product->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-700 rounded-lg text-xs font-semibold transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center">
                                        <p class="text-slate-600 font-semibold text-sm">Belum ada data produk</p>
                                        <p class="text-slate-400 text-xs mt-1">Klik <span class="text-indigo-600 font-semibold">+ Tambah Produk</span> untuk mulai mengisi katalog toko.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>