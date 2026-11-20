<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLog;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Halaman form Barang Masuk (Restock) & Penyesuaian Stok
     */
    public function index()
    {
        $products = Product::with('category')->orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->get();

        return view('admin.inventory.restock', compact('products', 'suppliers', 'lowStockProducts'));
    }

    /**
     * Proses simpan Barang Masuk (Restock -> type: 'in')
     */
    public function storeRestock(Request $request)
    {
        $validated = $request->validate([
            'product_id'  => ['required', 'exists:products,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'qty'         => ['required', 'integer', 'min:1'],
            'notes'       => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $product->increment('stock', $validated['qty']);

            StockLog::create([
                'product_id'  => $product->id,
                'user_id'     => $request->user()->id,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'type'        => 'in',
                'qty'         => $validated['qty'],
                'notes'       => $validated['notes'] ?: 'Restock barang masuk',
            ]);
        });

        return redirect()
            ->route('admin.inventory.restock')
            ->with('success', 'Stok barang berhasil ditambahkan dan tercatat di riwayat stok.');
    }

    /**
     * Proses simpan Penyesuaian Stok / Stock Adjustment (type: 'out')
     * Digunakan untuk barang rusak, hilang, atau kedaluwarsa
     */
    public function storeAdjustment(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qty'        => ['required', 'integer', 'min:1'],
            'notes'      => ['required', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($validated['qty'] > $product->stock) {
            return back()
                ->withInput()
                ->withErrors(['qty' => "Jumlah penyesuaian melebihi stok tersedia saat ini ({$product->stock})."]);
        }

        DB::transaction(function () use ($validated, $request) {
            $lockedProduct = Product::lockForUpdate()->findOrFail($validated['product_id']);

            if ($validated['qty'] > $lockedProduct->stock) {
                throw new \RuntimeException('Stok tidak mencukupi untuk penyesuaian.');
            }

            $lockedProduct->decrement('stock', $validated['qty']);

            StockLog::create([
                'product_id'  => $lockedProduct->id,
                'user_id'     => $request->user()->id,
                'supplier_id' => null,
                'type'        => 'out',
                'qty'         => $validated['qty'],
                'notes'       => 'Penyesuaian stok: ' . $validated['notes'],
            ]);
        });

        return redirect()
            ->route('admin.inventory.restock')
            ->with('success', 'Penyesuaian stok berhasil disimpan.');
    }

    /**
     * Halaman Riwayat Pergerakan Stok (Stock Logs)
     */
    public function logs(Request $request)
    {
        $query = StockLog::with(['product', 'user', 'supplier'])->latest();

        if ($request->filled('type') && in_array($request->type, ['in', 'out'], true)) {
            $query->where('type', $request->type);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $logs = $query->paginate(15)->withQueryString();
        $products = Product::orderBy('name')->get();

        return view('admin.inventory.logs', compact('logs', 'products'));
    }
}