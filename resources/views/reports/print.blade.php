<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Retailify ({{ $startDate }} - {{ $endDate }})</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 p-6 sm:p-10 antialiased">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
        
        <!-- Tombol Cetak -->
        <div class="no-print flex justify-between items-center pb-6 mb-6 border-b border-slate-200">
            <span class="text-xs text-slate-500">Pratinjau Dokumen Laporan Resmi</span>
            <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700">
                Cetak / Simpan sebagai PDF
            </button>
        </div>

        <!-- Kop Laporan -->
        <div class="flex items-start justify-between border-b-2 border-slate-900 pb-5 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">RETAILIFY SMART POS</h1>
                <p class="text-xs text-slate-500 mt-1">Laporan Rekapitulasi Penjualan &amp; Analisis Laba</p>
            </div>
            <div class="text-right text-xs">
                <p class="font-bold text-slate-800">Periode Laporan:</p>
                <p class="text-slate-600">{{ $startDate }} s/d {{ $endDate }}</p>
                <p class="text-slate-400 mt-1">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Ringkasan Angka -->
        <div class="grid grid-cols-3 gap-4 mb-6 text-xs">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <p class="text-slate-500">Total Pendapatan</p>
                <p class="text-lg font-extrabold text-slate-900 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <p class="text-slate-500">Total Modal (HPP)</p>
                <p class="text-lg font-extrabold text-slate-900 mt-1">Rp {{ number_format($totalCost, 0, ',', '.') }}</p>
            </div>
            <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-200">
                <p class="text-indigo-700 font-semibold">Estimasi Laba Kotor</p>
                <p class="text-lg font-extrabold text-indigo-700 mt-1">Rp {{ number_format($grossProfit, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <table class="w-full text-left text-xs border-collapse border border-slate-300">
            <thead class="bg-slate-100">
                <tr>
                    <th class="border border-slate-300 px-3 py-2">No. Invoice</th>
                    <th class="border border-slate-300 px-3 py-2">Tanggal</th>
                    <th class="border border-slate-300 px-3 py-2">Kasir</th>
                    <th class="border border-slate-300 px-3 py-2">Item Terjual</th>
                    <th class="border border-slate-300 px-3 py-2 text-right">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                    <tr>
                        <td class="border border-slate-300 px-3 py-2 font-bold">{{ $trx->invoice_number }}</td>
                        <td class="border border-slate-300 px-3 py-2">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td class="border border-slate-300 px-3 py-2">{{ $trx->user->name ?? '-' }}</td>
                        <td class="border border-slate-300 px-3 py-2">
                            {{ $trx->details->map(fn($d) => ($d->product->name ?? 'Item') . ' (' . $d->qty . 'x)')->implode(', ') }}
                        </td>
                        <td class="border border-slate-300 px-3 py-2 text-right font-bold">{{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="border border-slate-300 px-3 py-6 text-center text-slate-400">Tidak ada data transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</body>
</html>