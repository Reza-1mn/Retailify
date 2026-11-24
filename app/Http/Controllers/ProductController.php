<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'sku'          => 'required|string|max:50|unique:products,sku',
            'name'         => 'required|string|max:150',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'buy_price'    => 'required|numeric|min:0',
            'sell_price'   => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'min_stock'    => 'required|integer|min:0',
            'expired_date' => 'nullable|date',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'sku'          => 'required|string|max:50|unique:products,sku,' . $product->id,
            'name'         => 'required|string|max:150',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'buy_price'    => 'required|numeric|min:0',
            'sell_price'   => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'min_stock'    => 'required|integer|min:0',
            'expired_date' => 'nullable|date',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $statusText = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Produk {$product->name} berhasil {$statusText}.");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:products,id',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        $products = Product::whereIn('id', $request->ids)->get();

        if ($request->action === 'activate') {
            Product::whereIn('id', $request->ids)->update(['is_active' => true]);
            return back()->with('success', count($request->ids) . ' produk berhasil diaktifkan.');
        }

        if ($request->action === 'deactivate') {
            Product::whereIn('id', $request->ids)->update(['is_active' => false]);
            return back()->with('success', count($request->ids) . ' produk berhasil dinonaktifkan.');
        }

        foreach ($products as $product) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
        }

        return back()->with('success', count($request->ids) . ' produk berhasil dihapus.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}