<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Produk</h2>
            <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">+ Tambah Produk</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 px-3">SKU</th>
                            <th class="py-2 px-3">Nama Produk</th>
                            <th class="py-2 px-3">Kategori</th>
                            <th class="py-2 px-3">Harga Beli</th>
                            <th class="py-2 px-3">Harga Jual</th>
                            <th class="py-2 px-3">Stok</th>
                            <th class="py-2 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 px-3 font-mono text-sm">{{ $product->sku }}</td>
                                <td class="py-2 px-3 font-medium">{{ $product->name }}</td>
                                <td class="py-2 px-3">{{ $product->category->name ?? '-' }}</td>
                                <td class="py-2 px-3">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</td>
                                <td class="py-2 px-3">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                                <td class="py-2 px-3">
                                    {{ $product->stock }}
                                    @if($product->stock <= $product->min_stock)
                                        <span class="ml-1 px-2 py-0.5 text-xs bg-red-100 text-red-700 rounded-full font-semibold">Stok Menipis</span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-right space-x-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:underline text-sm">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-4 text-center text-gray-500">Belum ada data produk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>