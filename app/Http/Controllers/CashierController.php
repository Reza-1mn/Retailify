<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class CashierController extends Controller
{
    public function index()
    {
        $cashiers = User::where('role', 'kasir')->latest()->get();
        $admins   = User::where('role', 'admin')->latest()->get();

        return view('admin.cashiers.index', compact('cashiers', 'admins'));
    }

    public function create()
    {
        return view('admin.cashiers.create');
    }

    public function store(Request $request)
    {
        $request->merge(['email' => strtolower((string) $request->email)]);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role'              => 'kasir',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.cashiers.index')
            ->with('success', "Akun kasir {$validated['name']} berhasil dibuat.");
    }

    public function edit(User $cashier)
    {
        abort_if($cashier->role !== 'kasir', 403, 'Hanya akun kasir yang dapat dikelola di halaman ini.');

        return view('admin.cashiers.edit', compact('cashier'));
    }

    public function update(Request $request, User $cashier)
    {
        abort_if($cashier->role !== 'kasir', 403);

        $request->merge(['email' => strtolower((string) $request->email)]);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $cashier->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $cashier->update($data);

        return redirect()->route('admin.cashiers.index')
            ->with('success', "Data akun kasir {$cashier->name} berhasil diperbarui.");
    }

    public function destroy(User $cashier)
    {
        abort_if($cashier->role !== 'kasir' || $cashier->id === Auth::id(), 403);

        if ($cashier->avatar && Storage::disk('public')->exists($cashier->avatar)) {
            Storage::disk('public')->delete($cashier->avatar);
        }

        $name = $cashier->name;
        $cashier->delete();

        return redirect()->route('admin.cashiers.index')
            ->with('success', "Akun kasir {$name} berhasil dihapus.");
    }
}