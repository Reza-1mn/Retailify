<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLog;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        return view('pos.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'payment_amount' => 'required|numeric|min:0',
        ]);

        try {
            $transaction = DB::transaction(function () use ($validated, $request) {
                $totalAmount = 0;
                $preparedItems = [];

                foreach ($validated['items'] as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                    if ($product->stock < $item['qty']) {
                        throw new \Exception("Stok produk '{$product->name}' tidak mencukupi (tersisa {$product->stock}).");
                    }

                    $subtotal = $product->sell_price * $item['qty'];
                    $totalAmount += $subtotal;

                    $preparedItems[] = [
                        'product' => $product,
                        'qty' => $item['qty'],
                        'price' => $product->sell_price,
                        'subtotal' => $subtotal,
                    ];
                }

                if ($validated['payment_amount'] < $totalAmount) {
                    throw new \Exception('Nominal pembayaran kurang dari total belanja.');
                }

                $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));

                $transaction = Transaction::create([
                    'user_id' => $request->user()->id,
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => $totalAmount,
                    'payment_amount' => $validated['payment_amount'],
                    'change_amount' => $validated['payment_amount'] - $totalAmount,
                ]);

                foreach ($preparedItems as $item) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['product']->id,
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    // Kurangi stok produk
                    $item['product']->decrement('stock', $item['qty']);

                    // Catat pergerakan stok keluar ke stock_logs
                    StockLog::create([
                        'product_id' => $item['product']->id,
                        'user_id' => $request->user()->id,
                        'type' => 'out',
                        'qty' => $item['qty'],
                        'notes' => "Penjualan POS ({$invoiceNumber})",
                    ]);
                }

                return $transaction;
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan!',
                'invoice_number' => $transaction->invoice_number,
                'total_amount' => $transaction->total_amount,
                'payment_amount' => $transaction->payment_amount,
                'change_amount' => $transaction->change_amount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}