<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Laporan &amp; Analisis Bisnis</h1>
            <span class="hidden sm:inline-block text-slate-300">|</span>
            <span class="hidden sm:inline-block text-xs font-medium text-indigo-600">Khusus Admin</span>
        </div>
    </x-slot>

    <div class="p-5 sm:p-6 space-y-6 max-w-7xl mx-auto" x-data="{ activeTab: 'produk' }">

        <!-- Filter Periode & Tombol Unduh Laporan -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:bg-white focus:border-indigo-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                               class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:bg-white focus:border-indigo-600 outline-none">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full sm:w-auto px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm transition">
                            Terapkan Filter
                        </button>
                    </div>
                </div>

                <!-- Tombol Ekspor CSV & Cetak PDF -->
                <div class="flex flex-wrap items-center gap-2.5 pt-2 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                    <a href="{{ route('reports.export-csv', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                       class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Download Excel (.CSV)</span>
                    </a>

                    <a href="{{ route('reports.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank"
                       class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm shadow-indigo-600/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Cetak / Simpan PDF</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- 4 Kartu Ringkasan Analisis Keuangan & Stok -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500">Total Pendapatan Kotor</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1.5">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-[11px] text-slate-400 mt-1.5">Dari {{ $totalTransactions }} transaksi selesai</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500">Estimasi Laba Bersih</p>
                <p class="text-2xl font-extrabold text-emerald-600 mt-1.5">Rp {{ number_format($grossProfit, 0, ',', '.') }}</p>
                <p class="text-[11px] text-slate-400 mt-1.5">HPP Modal: Rp {{ number_format($totalCost, 0, ',', '.') }}</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500">Total Unit Terjual</p>
                <p class="text-2xl font-extrabold text-indigo-600 mt-1.5">{{ number_format($totalItemsSold, 0, ',', '.') }} <span class="text-sm font-semibold text-slate-400">pcs</span></p>
                <p class="text-[11px] text-slate-400 mt-1.5">Akumulasi item keluar kasir</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500">Arus Stok Periode Ini</p>
                <div class="flex items-center space-x-3 mt-1.5">
                    <span class="text-base font-extrabold text-emerald-600">+{{ $stockIn }} Masuk</span>
                    <span class="text-slate-300">|</span>
                    <span class="text-base font-extrabold text-rose-600">-{{ $stockOut }} Keluar</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">Tercatat di log inventori</p>
            </div>
        </div>

        <!-- Tabel Analisis dengan Tab Switcher -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex space-x-2">
                    <button @click="activeTab = 'produk'"
                            :class="activeTab === 'produk' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition">
                        Analisis Performa Produk
                    </button>
                    <button @click="activeTab = 'transaksi'"
                            :class="activeTab === 'transaksi' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition">
                        Rincian Transaksi ({{ $totalTransactions }})
                    </button>
                </div>
                <span class="text-xs text-slate-400">Periode: {{ $startDate }} s/d {{ $endDate }}</span>
            </div>

            <!-- Tab 1: Performa per Produk -->
            <div x-show="activeTab === 'produk'" class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5">SKU</th>
                            <th class="px-5 py-3.5">Nama Produk</th>
                            <th class="px-5 py-3.5">Kategori</th>
                            <th class="px-5 py-3.5 text-center">Terjual</th>
                            <th class="px-5 py-3.5 text-right">Omzet Produk</th>
                            <th class="px-5 py-3.5 text-right">Margin Laba</th>
                            <th class="px-5 py-3.5 text-center">Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($productSummary as $item)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-3 font-mono text-slate-500">{{ $item->sku }}</td>
                                <td class="px-5 py-3 font-bold text-slate-900">{{ $item->name }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $item->category }}</td>
                                <td class="px-5 py-3 text-center font-extrabold text-indigo-600">{{ $item->sold_qty }} pcs</td>
                                <td class="px-5 py-3 text-right font-semibold text-slate-800">Rp {{ number_format($item->revenue, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-right font-extrabold text-emerald-600">Rp {{ number_format($item->profit, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full font-bold {{ $item->current_stock <= 5 ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $item->current_stock }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada data produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tab 2: Rincian Transaksi -->
            <div x-show="activeTab === 'transaksi'" style="display: none;" class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5">No. Invoice</th>
                            <th class="px-5 py-3.5">Waktu</th>
                            <th class="px-5 py-3.5">Kasir</th>
                            <th class="px-5 py-3.5">Item Dibeli</th>
                            <th class="px-5 py-3.5 text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-3 font-bold text-indigo-600">{{ $trx->invoice_number }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-5 py-3 text-slate-700">{{ $trx->user->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-slate-600">
                                    {{ $trx->details->map(fn($d) => ($d->product->name ?? 'Item') . ' (' . $d->qty . 'x)')->implode(', ') }}
                                </td>
                                <td class="px-5 py-3 text-right font-extrabold text-slate-900">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">Tidak ada transaksi pada rentang tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>