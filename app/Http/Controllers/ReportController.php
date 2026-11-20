<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya dapat diakses oleh Admin.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Data Transaksi dalam rentang tanggal
        $transactions = Transaction::with(['user', 'details.product'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();

        // Hitung Estimasi HPP (Modal) & Laba Kotor
        $details = TransactionDetail::with('product')
            ->whereHas('transaction', fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->get();

        $totalCost = $details->sum(fn($d) => ($d->product->buy_price ?? 0) * $d->qty);
        $grossProfit = $totalRevenue - $totalCost;
        $totalItemsSold = $details->sum('qty');

        // Pergerakan Stok (Masuk vs Keluar)
        $stockIn = StockMovement::where('type', 'in')->whereBetween('created_at', [$start, $end])->sum('qty');
        $stockOut = StockMovement::where('type', 'out')->whereBetween('created_at', [$start, $end])->sum('qty');

        // Ringkasan Performa per Produk
        $productSummary = Product::with('category')->get()->map(function ($product) use ($details) {
            $soldQty = $details->where('product_id', $product->id)->sum('qty');
            $revenue = $details->where('product_id', $product->id)->sum('subtotal');
            $profit = $revenue - ($soldQty * $product->buy_price);

            return (object) [
                'sku' => $product->sku,
                'name' => $product->name,
                'category' => $product->category->name ?? '-',
                'current_stock' => $product->stock,
                'sold_qty' => $soldQty,
                'revenue' => $revenue,
                'profit' => $profit,
            ];
        })->sortByDesc('sold_qty');

        return view('reports.index', compact(
            'startDate',
            'endDate',
            'transactions',
            'totalRevenue',
            'totalTransactions',
            'totalCost',
            'grossProfit',
            'totalItemsSold',
            'stockIn',
            'stockOut',
            'productSummary'
        ));
    }

    public function exportCsv(Request $request)
    {
        $this->authorizeAdmin();

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $transactions = Transaction::with(['user', 'details.product'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        $filename = "Laporan_Retailify_{$startDate}_sd_{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            // Tambahkan BOM UTF-8 agar rapi saat dibuka langsung di Microsoft Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No. Invoice',
                'Tanggal & Waktu',
                'Nama Kasir',
                'Daftar Item Terjual',
                'Total Belanja (Rp)',
                'Tunai Diterima (Rp)',
                'Kembalian (Rp)',
            ]);

            foreach ($transactions as $trx) {
                $itemsText = $trx->details->map(function ($d) {
                    $name = $d->product->name ?? 'Produk';
                    return "{$name} ({$d->qty}x)";
                })->implode('; ');

                fputcsv($file, [
                    $trx->invoice_number,
                    $trx->created_at->format('Y-m-d H:i:s'),
                    $trx->user->name ?? '-',
                    $itemsText,
                    $trx->total_amount,
                    $trx->payment_amount,
                    $trx->change_amount,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printView(Request $request)
    {
        $this->authorizeAdmin();

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $transactions = Transaction::with(['user', 'details.product'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        $totalRevenue = $transactions->sum('total_amount');

        $details = TransactionDetail::with('product')
            ->whereHas('transaction', fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->get();

        $totalCost = $details->sum(fn($d) => ($d->product->buy_price ?? 0) * $d->qty);
        $grossProfit = $totalRevenue - $totalCost;

        return view('reports.print', compact(
            'startDate',
            'endDate',
            'transactions',
            'totalRevenue',
            'totalCost',
            'grossProfit'
        ));
    }
}