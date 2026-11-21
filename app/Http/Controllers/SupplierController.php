<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:150',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'phone'   => 'nullable|string|max:25',
            'address' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('suppliers', 'public');
        }

        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:150',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'phone'   => 'nullable|string|max:25',
            'address' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($supplier->image && Storage::disk('public')->exists($supplier->image)) {
                Storage::disk('public')->delete($supplier->image);
            }

            $validated['image'] = $request->file('image')->store('suppliers', 'public');
        } else {
            unset($validated['image']);
        }

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->image && Storage::disk('public')->exists($supplier->image)) {
            Storage::disk('public')->delete($supplier->image);
        }

        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}