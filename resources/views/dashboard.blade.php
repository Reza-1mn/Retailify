<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Dashboard Operasional</h1>
            <span class="hidden sm:inline-block text-slate-300">|</span>
            <span class="hidden sm:inline-block text-xs font-medium text-slate-500">
                {{ now()->format('d M Y') }}
            </span>
        </div>
    </x-slot>

    @php
        // 1. Satu Query Agregat untuk Penjualan 7 Hari Terakhir (termasuk Hari Ini & Kemarin)
        $startDate = today()->subDays(6)->startOfDay();
        $dailySales = \App\Models\Transaction::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $todayKey = today()->toDateString();
        $yesterdayKey = today()->subDay()->toDateString();

        $todaySales = (float) ($dailySales[$todayKey]->total ?? 0);
        $yesterdaySales = (float) ($dailySales[$yesterdayKey]->total ?? 0);
        $todayCount = (int) ($dailySales[$todayKey]->count ?? 0);

        $salesDiff = $yesterdaySales > 0
            ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100
            : ($todaySales > 0 ? 100 : 0);
        $avgOrderValue = $todayCount > 0 ? $todaySales / $todayCount : 0;

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = (float) ($dailySales[$date->toDateString()]->total ?? 0);
        }

        // 2. Satu Query Agregat Total Katalog & Stok + Daftar Stok Menipis
        $productStats = \App\Models\Product::selectRaw('COUNT(*) as total_sku, COALESCE(SUM(stock), 0) as total_units')->first();
        $totalProducts = (int) ($productStats->total_sku ?? 0);
        $totalStockUnits = (int) ($productStats->total_units ?? 0);
        $lowStockProducts = \App\Models\Product::whereColumn('stock', '<=', 'min_stock')->orderBy('stock')->get();

        // 3. Top 4 Produk Terlaris
        $topProducts = \App\Models\TransactionDetail::selectRaw('product_id, SUM(qty) as total_sold, SUM(subtotal) as total_revenue')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(4)
            ->get();
        $maxSold = $topProducts->max('total_sold') ?: 1;

        // 4. 6 Transaksi Terbaru
        $latestTransactions = \App\Models\Transaction::with('user')
            ->withCount('details')
            ->latest()
            ->take(6)
            ->get();
    @endphp

    <div class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Baris Atas: Judul Seksi & Aksi Cepat -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">Ringkasan Penjualan &amp; Inventori</h2>
                <p class="text-xs text-slate-500">Pantau performa transaksi kasir dan ketersediaan stok secara langsung.</p>
            </div>
            <div class="flex items-center space-x-2.5">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.inventory.restock') }}"
                       class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-xs shadow-xs transition">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Restock &amp; Penyesuaian</span>
                    </a>
                @endif
                <a href="{{ route('pos.index') }}"
                   class="btn-primary-theme inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl font-bold text-xs shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Transaksi Kasir Baru</span>
                </a>
            </div>
        </div>

        <!-- 4 Kartu KPI Padat & Fungsional -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- KPI 1: Omzet Hari Ini -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Omzet Hari Ini</span>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-slate-900 mt-2">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
                <div class="flex items-center space-x-1.5 mt-2 text-[11px]">
                    <span class="font-bold {{ $salesDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $salesDiff >= 0 ? '+' : '' }}{{ number_format($salesDiff, 0) }}%
                    </span>
                    <span class="text-slate-400">vs kemarin (Rp {{ number_format($yesterdaySales, 0, ',', '.') }})</span>
                </div>
            </div>

            <!-- KPI 2: Jumlah Transaksi & Rata-rata Struk -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Transaksi Selesai</span>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-slate-900 mt-2">{{ $todayCount }} <span class="text-sm font-semibold text-slate-400">struk</span></p>
                <p class="text-[11px] text-slate-400 mt-2">
                    Rata-rata struk: <strong class="text-slate-600">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</strong>
                </p>
            </div>

            <!-- KPI 3: Total Inventori -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Total Katalog &amp; Stok</span>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-slate-900 mt-2">{{ $totalProducts }} <span class="text-sm font-semibold text-slate-400">SKU</span></p>
                <p class="text-[11px] text-slate-400 mt-2">
                    Total fisik: <strong class="text-slate-600">{{ number_format($totalStockUnits, 0, ',', '.') }} unit</strong> tersedia
                </p>
            </div>

            <!-- KPI 4: Status Stok Menipis -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Stok Perlu Restock</span>
                    <div class="w-8 h-8 rounded-lg {{ $lowStockProducts->count() > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold {{ $lowStockProducts->count() > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-2">
                    {{ $lowStockProducts->count() }} <span class="text-sm font-semibold text-slate-400">produk</span>
                </p>
                <p class="text-[11px] text-slate-400 mt-2">
                    {{ $lowStockProducts->count() > 0 ? 'Segera lakukan restock barang' : 'Seluruh stok di atas batas minimum' }}
                </p>
            </div>
        </div>

        <!-- Baris Tengah: Grafik Tren Penjualan (Kiri) & Produk Terlaris (Kanan) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Grafik Penjualan 7 Hari Terakhir -->
            <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Tren Pendapatan (7 Hari Terakhir)</h3>
                        <p class="text-xs text-slate-400">Akumulasi total transaksi kasir harian</p>
                    </div>
                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">Mingguan</span>
                </div>
                <div class="h-60 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Produk Terlaris -->
            <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-extrabold text-slate-900">Produk Terlaris</h3>
                        <span class="text-[11px] font-semibold text-slate-400">Berdasarkan Qty</span>
                    </div>

                    <div class="space-y-4">
                        @forelse($topProducts as $item)
                            @php
                                $pct = round(($item->total_sold / $maxSold) * 100);
                            @endphp
                            <div>
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="font-bold text-slate-800 truncate pr-2">{{ $item->product->name ?? 'Produk Dihapus' }}</span>
                                    <span class="font-extrabold text-indigo-600 shrink-0">{{ $item->total_sold }} terjual</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">
                                    Pendapatan: Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                                </p>
                            </div>
                        @empty
                            <div class="py-12 text-center text-xs text-slate-400">
                                Belum ada data penjualan produk.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 text-right">
                    <a href="{{ route('pos.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                        Ke Halaman Kasir &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- Baris Bawah: Tabel Transaksi Terakhir & Peringatan Stok -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Tabel Riwayat Transaksi -->
            <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900">Riwayat Transaksi Terakhir</h3>
                    <span class="text-xs text-slate-400">6 transaksi terbaru</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-200/80">
                            <tr>
                                <th class="px-5 py-3">Invoice</th>
                                <th class="px-5 py-3">Kasir</th>
                                <th class="px-5 py-3">Item</th>
                                <th class="px-5 py-3">Total Bayar</th>
                                <th class="px-5 py-3">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($latestTransactions as $trx)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-5 py-3 font-mono font-bold text-indigo-600">{{ $trx->invoice_number }}</td>
                                    <td class="px-5 py-3 font-medium text-slate-700">{{ $trx->user->name ?? '-' }}</td>
                                    <td class="px-5 py-3 text-slate-500">{{ $trx->details_count }} jenis</td>
                                    <td class="px-5 py-3 font-extrabold text-slate-900">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-slate-400">{{ $trx->created_at->format('d M, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                        Belum ada transaksi tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Panel Peringatan Stok Menipis -->
            <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-extrabold text-slate-900">Monitoring Batas Stok</h3>
                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-md border {{ $lowStockProducts->count() > 0 ? 'bg-rose-50 border-rose-200 text-rose-600' : 'bg-slate-50 border-slate-200 text-slate-600' }}">
                            {{ $lowStockProducts->count() }} Alert
                        </span>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($lowStockProducts as $item)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                                <div class="min-w-0 pr-2">
                                    <p class="font-bold text-slate-800 truncate">{{ $item->name }}</p>
                                    <p class="text-[11px] text-slate-500 font-mono">SKU: {{ $item->sku }} &bull; Min: {{ $item->min_stock }}</p>
                                </div>
                                <span class="shrink-0 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 border border-rose-200 font-extrabold">
                                    Sisa {{ $item->stock }}
                                </span>
                            </div>
                        @empty
                            <div class="py-10 text-center border border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                                <p class="text-xs font-semibold text-slate-500">Semua stok dalam kondisi aman</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Tidak ada produk di bawah batas minimum.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if(Auth::user()->role === 'admin')
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('admin.inventory.restock') }}"
                           class="text-xs font-bold text-indigo-600 hover:underline">
                            Kelola Restock &rarr;
                        </a>
                        <a href="{{ route('admin.inventory.logs') }}"
                           class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                            Riwayat Stok
                        </a>
                    </div>
                @endif
            </div>

        </div>

    </div>

    <!-- Chart.js dengan Debounce Observer (Anti-Freeze) & Sinkron Tema -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            let salesChartInstance = null;
            let themeTimeout = null;

            function getThemeVars() {
                const styles = getComputedStyle(document.documentElement);
                return {
                    line: styles.getPropertyValue('--chart-line').trim() || '#6366f1',
                    fillTop: styles.getPropertyValue('--chart-fill-top').trim() || 'rgba(99, 102, 241, 0.22)',
                    fillBottom: styles.getPropertyValue('--chart-fill-bottom').trim() || 'rgba(99, 102, 241, 0)',
                    grid: styles.getPropertyValue('--chart-grid').trim() || '#f1f5f9',
                    tick: styles.getPropertyValue('--chart-tick').trim() || '#64748b',
                    pointBg: styles.getPropertyValue('--chart-point-bg').trim() || '#ffffff',
                };
            }

            function initSalesChart() {
                const canvas = document.getElementById('salesChart');
                if (!canvas || typeof Chart === 'undefined') return;

                if (salesChartInstance) {
                    salesChartInstance.destroy();
                }

                const ctx = canvas.getContext('2d');
                const v = getThemeVars();
                const gradient = ctx.createLinearGradient(0, 0, 0, 220);
                gradient.addColorStop(0, v.fillTop);
                gradient.addColorStop(1, v.fillBottom);

                salesChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Omzet (Rp)',
                            data: @json($chartData),
                            borderColor: v.line,
                            backgroundColor: gradient,
                            borderWidth: 2.5,
                            pointBackgroundColor: v.pointBg,
                            pointBorderColor: v.line,
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            fill: true,
                            tension: 0.35
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (c) => ' Rp ' + Number(c.raw).toLocaleString('id-ID')
                                }
                            }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: v.tick } },
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: v.grid },
                                ticks: {
                                    font: { size: 11 },
                                    color: v.tick,
                                    callback: (val) => 'Rp ' + Number(val).toLocaleString('id-ID')
                                }
                            }
                        }
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', initSalesChart);

            const observer = new MutationObserver(() => {
                clearTimeout(themeTimeout);
                themeTimeout = setTimeout(() => {
                    if (!salesChartInstance) return;
                    const v = getThemeVars();
                    const ctx = salesChartInstance.ctx;
                    const g = ctx.createLinearGradient(0, 0, 0, 220);
                    g.addColorStop(0, v.fillTop);
                    g.addColorStop(1, v.fillBottom);

                    const ds = salesChartInstance.data.datasets[0];
                    ds.borderColor = v.line;
                    ds.backgroundColor = g;
                    ds.pointBackgroundColor = v.pointBg;
                    ds.pointBorderColor = v.line;
                    salesChartInstance.options.scales.x.ticks.color = v.tick;
                    salesChartInstance.options.scales.y.ticks.color = v.tick;
                    salesChartInstance.options.scales.y.grid.color = v.grid;
                    salesChartInstance.update('none');
                }, 150);
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class', 'data-accent']
            });
        })();
    </script>
</x-app-layout>