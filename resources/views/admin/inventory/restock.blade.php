<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Inventori & Restock Barang
            </h2>
            <a href="{{ route('admin.inventory.logs') }}"
               class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">
                Lihat Riwayat Stok
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($lowStockProducts->isNotEmpty())
                <div class="p-4 bg-amber-50 border border-amber-300 rounded-lg">
                    <p class="font-semibold text-amber-900 text-sm mb-2">
                        Peringatan Stok Menipis ({{ $lowStockProducts->count() }} produk):
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($lowStockProducts as $item)
                            <span class="px-2.5 py-1 text-xs font-medium bg-amber-200 text-amber-900 rounded-full">
                                {{ $item->sku }} — {{ $item->name }} (Sisa: {{ $item->stock }} / Min: {{ $item->min_stock }})
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Form Barang Masuk (Restock) --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Barang Masuk (Restock)</h3>

                    <form action="{{ route('admin.inventory.restock.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                            <select name="product_id" required class="w-full rounded-lg border-gray-300 text-sm">
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                        [{{ $product->sku }}] {{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier (Opsional)</label>
                            <select name="supplier_id" class="w-full rounded-lg border-gray-300 text-sm">
                                <option value="">-- Tanpa Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Masuk (Qty)</label>
                            <input type="number" name="qty" min="1" value="{{ old('qty') }}" required
                                   class="w-full rounded-lg border-gray-300 text-sm" placeholder="Contoh: 20">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                            <input type="text" name="notes" value="{{ old('notes') }}"
                                   class="w-full rounded-lg border-gray-300 text-sm" placeholder="Contoh: Pengiriman rutin mingguan">
                        </div>

                        <button type="submit"
                                class="w-full py-2.5 px-4 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                            Simpan Barang Masuk
                        </button>
                    </form>
                </div>

                {{-- Form Penyesuaian Stok (Barang Rusak / Hilang / Expired) --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Penyesuaian Stok Keluar</h3>

                    <form action="{{ route('admin.inventory.adjustment.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                            <select name="product_id" required class="w-full rounded-lg border-gray-300 text-sm">
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        [{{ $product->sku }}] {{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Dikurangi (Qty)</label>
                            <input type="number" name="qty" min="1" required
                                   class="w-full rounded-lg border-gray-300 text-sm" placeholder="Contoh: 2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penyesuaian</label>
                            <input type="text" name="notes" required
                                   class="w-full rounded-lg border-gray-300 text-sm" placeholder="Contoh: Kemasan penyok / kedaluwarsa">
                        </div>

                        <button type="submit"
                                class="w-full py-2.5 px-4 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700">
                            Catat Penyesuaian Stok
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>