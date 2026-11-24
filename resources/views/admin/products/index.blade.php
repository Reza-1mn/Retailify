<x-app-layout>
    @php
        $totalCount = $products->count();
        $activeCount = $products->where('is_active', true)->count();
        $inactiveCount = $products->where('is_active', false)->count();
        $lowStockItems = $products->filter(fn($p) => $p->stock <= $p->min_stock);
        $noPhotoItems = $products->filter(fn($p) => empty($p->image));
        $expiringItems = $products->filter(fn($p) => $p->isExpired() || $p->isNearExpiry(30));
    @endphp

    <div x-data="{
            tab: 'all',
            search: '',
            categoryFilter: 'all',
            selectedIds: [],
            allIds: {{ $products->pluck('id')->toJson() }},
            toggleAll() {
                if (this.selectedIds.length === this.allIds.length) {
                    this.selectedIds = [];
                } else {
                    this.selectedIds = [...this.allIds];
                }
            },
            submitBulk(actionName) {
                if (this.selectedIds.length === 0) return;
                if (actionName === 'delete' && !confirm('Hapus ' + this.selectedIds.length + ' produk terpilih secara permanen?')) return;
                document.getElementById('bulkActionInput').value = actionName;
                document.getElementById('bulkActionForm').submit();
            }
         }"
         class="min-h-screen bg-slate-50/70 pb-12">

        <div class="w-full px-4 sm:px-6 lg:px-8 pt-6 space-y-6">

            <!-- Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-1">
                        <span>Manajemen Toko</span>
                        <span>/</span>
                        <span class="text-slate-700 font-semibold">Manage Products</span>
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manage Products</h1>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('admin.categories.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold shadow-xs transition">
                        <span>Kelola Kategori</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}"
                       class="btn-primary-theme inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Product</span>
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="flex items-center gap-3 p-4 bg-white border border-slate-200 text-slate-800 rounded-2xl shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Ringkasan Kondisi Katalog -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs space-y-4">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h2 class="text-sm font-bold text-slate-800">Ringkasan Kondisi Katalog</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Kartu 1: Low Stock -->
                    <button type="button" @click="tab = 'low_stock'"
                            class="text-left p-4 rounded-xl bg-slate-50/70 hover:bg-slate-100/70 border border-slate-200/80 transition group flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-slate-900">Stok Menipis (Low Stock)</p>
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-md border border-slate-200 bg-white {{ $lowStockItems->count() > 0 ? 'text-rose-600' : 'text-slate-600' }}">
                                    {{ $lowStockItems->count() }} item
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">Segera lakukan restock agar kasir tidak kehabisan barang saat transaksi.</p>
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-200/60">
                            <div class="flex -space-x-1.5">
                                @forelse($lowStockItems->take(3) as $item)
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" class="w-8 h-8 rounded-full object-cover ui-avatar-pill">
                                    @else
                                        <div class="w-8 h-8 rounded-full ui-avatar-pill text-[11px] font-extrabold flex items-center justify-center uppercase">
                                            {{ substr($item->name, 0, 2) }}
                                        </div>
                                    @endif
                                @empty
                                    <span class="text-xs text-slate-500 font-medium">Semua stok dalam batas aman</span>
                                @endforelse
                            </div>
                            <span class="text-slate-400 group-hover:text-slate-700 text-sm font-bold">&rsaquo;</span>
                        </div>
                    </button>

                    <!-- Kartu 2: Kadaluarsa / Mendekati Kadaluarsa -->
                    <button type="button" @click="tab = 'expiring'"
                            class="text-left p-4 rounded-xl bg-slate-50/70 hover:bg-slate-100/70 border border-slate-200/80 transition group flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-slate-900">Mendekati / Kadaluarsa</p>
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-md border border-slate-200 bg-white {{ $expiringItems->count() > 0 ? 'text-amber-600' : 'text-slate-600' }}">
                                    {{ $expiringItems->count() }} item
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">Pantau produk yang sudah lewat atau akan kadaluarsa dalam 30 hari.</p>
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-200/60">
                            <span class="text-xs font-semibold {{ $expiringItems->count() > 0 ? 'text-amber-600' : 'text-slate-500' }}">
                                {{ $expiringItems->count() > 0 ? 'Periksa tanggal kadaluarsa' : 'Tidak ada produk hampir kadaluarsa' }}
                            </span>
                            <span class="text-slate-400 group-hover:text-slate-700 text-sm font-bold">&rsaquo;</span>
                        </div>
                    </button>

                    <!-- Kartu 3: Lengkapi Foto Produk -->
                    <button type="button" @click="tab = 'no_photo'"
                            class="text-left p-4 rounded-xl bg-slate-50/70 hover:bg-slate-100/70 border border-slate-200/80 transition group flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-slate-900">Lengkapi Foto Produk</p>
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-md border border-slate-200 bg-white text-slate-700">
                                    {{ $noPhotoItems->count() }} item
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">Tambahkan foto produk agar tampilan grid di halaman Kasir (POS) lebih visual.</p>
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-200/60">
                            <div class="flex -space-x-1.5">
                                @forelse($noPhotoItems->take(3) as $item)
                                    <div class="w-8 h-8 rounded-full ui-avatar-pill text-[11px] font-extrabold flex items-center justify-center uppercase">
                                        {{ substr($item->name, 0, 2) }}
                                    </div>
                                @empty
                                    <span class="text-xs text-slate-500 font-medium">Semua produk memiliki foto</span>
                                @endforelse
                            </div>
                            <span class="text-slate-400 group-hover:text-slate-700 text-sm font-bold">&rsaquo;</span>
                        </div>
                    </button>

                    <!-- Kartu 4: Produk Nonaktif -->
                    <button type="button" @click="tab = 'inactive'"
                            class="text-left p-4 rounded-xl bg-slate-50/70 hover:bg-slate-100/70 border border-slate-200/80 transition group flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-slate-900">Produk Nonaktif (Inactive)</p>
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-md border border-slate-200 bg-white text-slate-700">
                                    {{ $inactiveCount }} item
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">Produk nonaktif disembunyikan dari Kasir tanpa menghapus riwayat penjualannya.</p>
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-200/60">
                            <span class="text-xs font-semibold text-slate-600">Lihat daftar produk nonaktif</span>
                            <span class="text-slate-400 group-hover:text-slate-700 text-sm font-bold">&rsaquo;</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Container Utama: Tabs + Filter + Bulk Action + Tabel -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">

                <!-- Tab Navigasi Status -->
                <div class="px-6 pt-4 border-b border-slate-200/80 flex items-center gap-6 overflow-x-auto text-xs sm:text-sm">
                    <button type="button" @click="tab = 'all'"
                            :class="tab === 'all' ? 'border-indigo-600 text-slate-900 font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-800 font-semibold'"
                            class="pb-3.5 border-b-2 whitespace-nowrap transition">
                        All <span class="ml-1 text-xs text-slate-400 font-semibold">{{ $totalCount }}</span>
                    </button>

                    <button type="button" @click="tab = 'active'"
                            :class="tab === 'active' ? 'border-indigo-600 text-slate-900 font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-800 font-semibold'"
                            class="pb-3.5 border-b-2 whitespace-nowrap transition">
                        Active <span class="ml-1 text-xs text-slate-400 font-semibold">{{ $activeCount }}</span>
                    </button>

                    <button type="button" @click="tab = 'inactive'"
                            :class="tab === 'inactive' ? 'border-indigo-600 text-slate-900 font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-800 font-semibold'"
                            class="pb-3.5 border-b-2 whitespace-nowrap transition">
                        Inactive <span class="ml-1 text-xs text-slate-400 font-semibold">{{ $inactiveCount }}</span>
                    </button>

                    <button type="button" @click="tab = 'low_stock'"
                            :class="tab === 'low_stock' ? 'border-indigo-600 text-slate-900 font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-800 font-semibold'"
                            class="pb-3.5 border-b-2 whitespace-nowrap transition">
                        Low Stock <span class="ml-1 text-xs text-slate-400 font-semibold">{{ $lowStockItems->count() }}</span>
                    </button>

                    <button type="button" @click="tab = 'expiring'"
                            :class="tab === 'expiring' ? 'border-indigo-600 text-slate-900 font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-800 font-semibold'"
                            class="pb-3.5 border-b-2 whitespace-nowrap transition">
                        Expiring / Expired <span class="ml-1 text-xs text-slate-400 font-semibold">{{ $expiringItems->count() }}</span>
                    </button>
                </div>

                <!-- Bar Filter & Pencarian -->
                <div class="p-4 sm:px-6 bg-white border-b border-slate-200/80 space-y-3">
                    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                        <div class="relative flex-1">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                            <input type="text" x-model="search" placeholder="Cari nama produk atau kode SKU..."
                                   class="w-full pl-10 pr-4 py-2 bg-white border-slate-200 rounded-xl text-xs sm:text-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                        </div>

                        <select x-model="categoryFilter"
                                class="px-3.5 py-2 bg-white border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        <button type="button" @click="tab = 'all'; search = ''; categoryFilter = 'all'; selectedIds = []"
                                class="px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold transition">
                            Reset
                        </button>
                    </div>

                    <!-- Bar Bulk Action -->
                    <form id="bulkActionForm" action="{{ route('admin.products.bulk-action') }}" method="POST"
                          class="flex flex-wrap items-center gap-2 pt-1">
                        @csrf
                        <input type="hidden" name="action" id="bulkActionInput">
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>

                        <span class="text-xs font-bold text-slate-600 mr-1">
                            Selected: <strong class="text-slate-900" x-text="selectedIds.length"></strong>
                        </span>

                        <button type="button" @click="submitBulk('activate')" :disabled="selectedIds.length === 0"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 disabled:opacity-45 disabled:cursor-not-allowed transition">
                            Activate
                        </button>

                        <button type="button" @click="submitBulk('deactivate')" :disabled="selectedIds.length === 0"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 disabled:opacity-45 disabled:cursor-not-allowed transition">
                            Deactivate
                        </button>

                        <button type="button" @click="submitBulk('delete')" :disabled="selectedIds.length === 0"
                                class="btn-danger-outline px-3 py-1.5 rounded-lg text-xs font-bold">
                            Delete
                        </button>
                    </form>
                </div>

                <!-- Tabel Produk -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-5 w-10">
                                    <input type="checkbox" @click="toggleAll()"
                                           :checked="allIds.length > 0 && selectedIds.length === allIds.length"
                                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="py-3.5 px-4">Product</th>
                                <th class="py-3.5 px-4">SKU</th>
                                <th class="py-3.5 px-4">Stock</th>
                                <th class="py-3.5 px-4">Price</th>
                                <th class="py-3.5 px-4">Expired Date</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($products as $product)
                                @php
                                    $isLow = $product->stock <= $product->min_stock;
                                    $hasNoPhoto = empty($product->image);
                                    $isExpired = $product->isExpired();
                                    $isNearExpiry = $product->isNearExpiry(30);
                                @endphp
                                <tr x-show="
                                        (tab === 'all' ||
                                         (tab === 'active' && {{ $product->is_active ? 'true' : 'false' }}) ||
                                         (tab === 'inactive' && {{ !$product->is_active ? 'true' : 'false' }}) ||
                                         (tab === 'low_stock' && {{ $isLow ? 'true' : 'false' }}) ||
                                         (tab === 'no_photo' && {{ $hasNoPhoto ? 'true' : 'false' }}) ||
                                         (tab === 'expiring' && {{ ($isExpired || $isNearExpiry) ? 'true' : 'false' }})) &&
                                        (categoryFilter === 'all' || categoryFilter == '{{ $product->category_id }}') &&
                                        (search === '' || '{{ strtolower($product->sku . ' ' . $product->name) }}'.includes(search.toLowerCase()))
                                    "
                                    class="hover:bg-slate-50 transition-colors {{ !$product->is_active ? 'opacity-65' : '' }}">

                                    <td class="py-4 px-5">
                                        <input type="checkbox" value="{{ $product->id }}" x-model.number="selectedIds"
                                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    </td>

                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3.5">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                     class="w-11 h-11 rounded-xl object-cover border border-slate-200 shrink-0">
                                            @else
                                                <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs flex items-center justify-center uppercase shrink-0 border border-slate-200/80">
                                                    {{ substr($product->name, 0, 2) }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-900 truncate">{{ $product->name }}</p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
                                                        {{ $product->category->name ?? 'Umum' }}
                                                    </span>
                                                    <span class="text-[11px] text-slate-400">Modal: Rp {{ number_format($product->buy_price, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-4">
                                        <span class="font-mono text-xs font-semibold text-slate-600">
                                            {{ $product->sku }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-4">
                                        <span class="font-bold {{ $isLow ? 'text-rose-600' : 'text-slate-900' }}">{{ $product->stock }}</span>
                                        <span class="text-xs text-slate-400">pcs</span>
                                        @if($isLow)
                                            <p class="text-[10px] font-semibold text-rose-600 mt-0.5">Min. {{ $product->min_stock }}</p>
                                        @endif
                                    </td>

                                    <td class="py-4 px-4 font-bold text-slate-900">
                                        Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                    </td>

                                    <td class="py-4 px-4">
                                        @if($product->expired_date)
                                            <p class="text-xs font-bold {{ $isExpired ? 'text-rose-600' : ($isNearExpiry ? 'text-amber-600' : 'text-slate-700') }}">
                                                {{ $product->expired_date->format('d M Y') }}
                                            </p>
                                            @if($isExpired)
                                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-rose-50 text-rose-600 border border-rose-200/70">
                                                    Kadaluarsa
                                                </span>
                                            @elseif($isNearExpiry)
                                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200/70">
                                                    &le; 30 Hari Lagi
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-xs text-slate-400">—</span>
                                        @endif
                                    </td>

                                    <!-- Toggle Switch Active / Inactive -->
                                    <td class="py-4 px-4">
                                        <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="inline-flex items-center gap-2.5">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    title="Klik untuk {{ $product->is_active ? 'menonaktifkan' : 'mengaktifkan' }} produk"
                                                    class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out {{ $product->is_active ? 'bg-indigo-600' : 'bg-slate-300' }}">
                                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200 ease-in-out {{ $product->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                            </button>
                                            <span class="text-xs font-semibold {{ $product->is_active ? 'text-slate-800' : 'text-slate-400' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </form>
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center gap-2 text-xs font-bold">
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="px-2.5 py-1 rounded-lg text-slate-700 hover:bg-slate-100 transition">
                                                Edit
                                            </a>
                                            <span class="text-slate-300">|</span>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk {{ $product->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger-soft px-2.5 py-1 rounded-lg font-bold">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-16 text-center">
                                        <p class="text-slate-600 font-semibold text-sm">Belum ada data produk</p>
                                        <p class="text-slate-400 text-xs mt-1">Klik <span class="text-indigo-600 font-semibold">+ Add Product</span> untuk menambahkan produk pertama.</p>
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