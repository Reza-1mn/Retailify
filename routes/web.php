<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/inventory/restock', [InventoryController::class, 'index'])->name('inventory.restock');
    Route::post('/inventory/restock', [InventoryController::class, 'storeRestock'])->name('inventory.restock.store');
    Route::post('/inventory/adjustment', [InventoryController::class, 'storeAdjustment'])->name('inventory.adjustment.store');
    Route::get('/inventory/logs', [InventoryController::class, 'logs'])->name('inventory.logs');
});

require __DIR__.'/auth.php';
