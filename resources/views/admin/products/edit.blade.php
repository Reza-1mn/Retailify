<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Produk</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('admin.products.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full border-gray-300 rounded shadow-sm" required>
                            @error('sku')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="category_id" class="w-full border-gray-300 rounded shadow-sm" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border-gray-300 rounded shadow-sm" required>
                        @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli (Rp)</label>
                            <input type="number" name="buy_price" value="{{ old('buy_price', $product->buy_price) }}" min="0" class="w-full border-gray-300 rounded shadow-sm" required>
                            @error('buy_price')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp)</label>
                            <input type="number" name="sell_price" value="{{ old('sell_price', $product->sell_price) }}" min="0" class="w-full border-gray-300 rounded shadow-sm" required>
                            @error('sell_price')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="w-full border-gray-300 rounded shadow-sm" required>
                            @error('stock')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Batas Minimum Stok</label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0" class="w-full border-gray-300 rounded shadow-sm" required>
                            @error('min_stock')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border rounded text-sm">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Update Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>