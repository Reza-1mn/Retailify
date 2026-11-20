<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Riwayat Pergerakan Stok (Stock Logs)
            </h2>
            <a href="{{ route('admin.inventory.restock') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                + Barang Masuk / Penyesuaian
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter Riwayat --}}
            <form method="GET" action="{{ route('admin.inventory.logs') }}"
                  class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Mutasi</label>
                    <select name="type" class="rounded-lg border-gray-300 text-sm">
                        <option value="">Semua</option>
                        <option value="in" @selected(request('type') === 'in')>Masuk (IN)</option>
                        <option value="out" @selected(request('type') === 'out')>Keluar (OUT)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Produk</label>
                    <select name="product_id" class="rounded-lg border-gray-300 text-sm">
                        <option value="">Semua Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">
                        Filter
                    </button>
                    <a href="{{ route('admin.inventory.logs') }}"
                       class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
                        Reset
                    </a>
                </div>
            </form>

            {{-- Tabel Stock Logs --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-left">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Waktu</th>
                            <th class="px-4 py-3 font-semibold">Produk</th>
                            <th class="px-4 py-3 font-semibold">Jenis</th>
                            <th class="px-4 py-3 font-semibold">Qty</th>
                            <th class="px-4 py-3 font-semibold">Supplier</th>
                            <th class="px-4 py-3 font-semibold">Petugas</th>
                            <th class="px-4 py-3 font-semibold">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($logs as $log)
                            <tr>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $log->product->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($log->type === 'in')
                                        <span class="px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                            Masuk (+{{ $log->qty }})
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                            Keluar (-{{ $log->qty }})
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-semibold">{{ $log->qty }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $log->supplier->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $log->user->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $log->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada riwayat pergerakan stok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $logs->links() }}
            </div>

        </div>
    </div>
</x-app-layout>