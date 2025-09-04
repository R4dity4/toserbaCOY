<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\AuthController;



// Guest only routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        $produk = \App\Models\Produk::with('harga')->where('status','aktif')->latest()->take(12)->get();
        return view('welcome', compact('produk'));
    });
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout (hanya untuk yang sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function() {
        $totalProduk = \App\Models\Produk::count();
        $totalUser = \App\Models\User::count();
        $totalStok = \App\Models\Stok::sum('jumlah_stok');
        return view('admin.dashboard', compact('totalProduk', 'totalUser', 'totalStok'));
    })->name('admin.dashboard');
    // Semua route admin yang sudah ada
    Route::resource('produk', ProdukController::class);
    Route::get('/stok-in', [StokController::class, 'stokInForm'])->name('stok.in.form');
    Route::post('/stok-in', [StokController::class, 'stokInStore'])->name('stok.in.store');
    Route::get('/stok-in/history', [StokController::class, 'stokInIndex'])->name('stok.in.index');
    Route::get('/stok-out', [StokController::class, 'stokOutForm'])->name('stok.out.form');
    Route::post('/stok-out', [StokController::class, 'stokOutStore'])->name('stok.out.store');
    Route::get('/stok-out/history', [StokController::class, 'stokOutIndex'])->name('stok.out.index');
    Route::get('/riwayat-stok', [StokController::class, 'riwayatStok'])->name('stok.riwayat');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user', function() { return view('user.dashboard'); })->name('user.dashboard');
    Route::get('/shop', function() {
        $produk = \App\Models\Produk::with('harga')->where('status','aktif')->get();
        return view('user.shop', compact('produk'));
    })->name('user.shop');
    // Cart
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('user.cart');
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
});

// Produk Routes
// Route::resource('produk', ProdukController::class);

// // Stok Routes
// Route::get('/stok-in', [StokController::class, 'stokInForm'])->name('stok.in.form');
// Route::post('/stok-in', [StokController::class, 'stokInStore'])->name('stok.in.store');
// Route::get('/stok-in/history', [StokController::class, 'stokInIndex'])->name('stok.in.index');
// Route::get('/stok-out', [StokController::class, 'stokOutForm'])->name('stok.out.form');
// Route::post('/stok-out', [StokController::class, 'stokOutStore'])->name('stok.out.store');
// Route::get('/stok-out/history', [StokController::class, 'stokOutIndex'])->name('stok.out.index');
// Route::get('/riwayat-stok', [StokController::class, 'riwayatStok'])->name('stok.riwayat');
