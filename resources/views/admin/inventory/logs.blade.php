<x-app-layout>
    <div class="min-h-screen bg-slate-50/70 pb-12">
        <div class="bg-white border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-sky-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-0.5">
                                <span>Manajemen Toko</span>
                                <span>/</span>
                                <span class="text-sky-600">Inventori</span>
                            </div>
                            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat Pergerakan Stok</h1>
                        </div>
                    </div>

                    <a href="{{ route('admin.inventory.restock') }}"
                       class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm shadow-indigo-200 transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Restock / Penyesuaian
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-6">
            @php
                $incomingCount = $logs->where('type', 'in')->count();
                $outgoingCount = $logs->where('type', 'out')->count();
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Riwayat</p>
                        <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $logs->total() }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Halaman Saat Ini</p>
                        <p class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $incomingCount }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mutasi Keluar</p>
                        <p class="text-3xl font-extrabold text-rose-600 mt-1">{{ $outgoingCount }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m-2 0l8-8m-8 8l8 8"/></svg>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.inventory.logs') }}"
                  class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex flex-col md:flex-row md:items-end gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-600 mb-2">Jenis Mutasi</label>
                    <select name="type" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white">
                        <option value="">Semua Mutasi</option>
                        <option value="in" @selected(request('type') === 'in')>Masuk (IN)</option>
                        <option value="out" @selected(request('type') === 'out')>Keluar (OUT)</option>
                    </select>
                </div>
                <div class="flex-[1.5]">
                    <label class="block text-xs font-bold text-slate-600 mb-2">Produk</label>
                    <select name="product_id" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white">
                        <option value="">Semua Produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white border border-indigo-600 rounded-xl text-xs font-bold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.inventory.logs') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition">
                        Reset
                    </a>
                </div>
            </form>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200/80 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-6">Waktu</th>
                                <th class="py-4 px-6">Produk</th>
                                <th class="py-4 px-6">Jenis</th>
                                <th class="py-4 px-6">Qty</th>
                                <th class="py-4 px-6">Supplier</th>
                                <th class="py-4 px-6">Petugas</th>
                                <th class="py-4 px-6">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($logs as $log)
                                <tr class="hover:bg-slate-50/90 transition-colors">
                                    <td class="py-4 px-6 text-slate-500 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-4 px-6">
                                        <p class="font-bold text-slate-800">{{ $log->product->name ?? '-' }}</p>
                                        @if($log->product)
                                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $log->product->sku }}</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($log->type === 'in')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold bg-emerald-50 text-emerald-700 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Masuk (+{{ $log->qty }})
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold bg-rose-50 text-rose-700 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Keluar (-{{ $log->qty }})
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 font-extrabold text-slate-800">{{ $log->qty }}</td>
                                    <td class="py-4 px-6 text-slate-600">{{ $log->supplier->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-slate-600">{{ $log->user->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-slate-500 max-w-xs">{{ $log->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-16 text-center">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        </div>
                                        <p class="text-slate-600 font-semibold text-sm">Belum ada riwayat pergerakan stok</p>
                                        <p class="text-slate-400 text-xs mt-1">Perubahan stok akan tampil otomatis di halaman ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($logs->hasPages())
                <div class="flex justify-end">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
