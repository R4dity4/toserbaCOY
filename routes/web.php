<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;



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
    // Socialite Google OAuth
    Route::get('/auth/google/redirect', [\App\Http\Controllers\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [\App\Http\Controllers\SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
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
    Route::get('/shop', [\App\Http\Controllers\ShopController::class, 'index'])->name('user.shop');
    // Wishlist
    Route::post('/wishlist/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('user.wishlist');
    // Cart
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('user.cart');
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');

    // Orders & Payment
    Route::get('/user/orders', [OrderController::class, 'index'])->name('user.orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/paid', [OrderController::class, 'markPaid'])->name('orders.paid');
    // Invoice
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('orders.invoice');
    Route::get('/orders/{order}/invoice/download', [\App\Http\Controllers\InvoiceController::class, 'download'])->name('orders.invoice.download');
});

// Public product detail route (anyone can view product page)
Route::get('/product/{produk}', [\App\Http\Controllers\ShopController::class, 'show'])->name('product.show');

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
