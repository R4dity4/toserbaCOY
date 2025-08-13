<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StokController;

Route::get('/', function () {
    return view('welcome');
});

// Produk Routes
Route::resource('produk', ProdukController::class);

// Stok Routes
Route::get('/stok-in', [StokController::class, 'stokInForm'])->name('stok.in.form');
Route::post('/stok-in', [StokController::class, 'stokInStore'])->name('stok.in.store');
Route::get('/stok-in/history', [StokController::class, 'stokInIndex'])->name('stok.in.index');
Route::get('/stok-out', [StokController::class, 'stokOutForm'])->name('stok.out.form');
Route::post('/stok-out', [StokController::class, 'stokOutStore'])->name('stok.out.store');
Route::get('/stok-out/history', [StokController::class, 'stokOutIndex'])->name('stok.out.index');
Route::get('/riwayat-stok', [StokController::class, 'riwayatStok'])->name('stok.riwayat');
