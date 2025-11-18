<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Wishlist;

class ShopController extends Controller
{
    public function index()
    {
        // load active products with harga relation
        $produk = Produk::with('harga')->where('status','aktif')->get();
        // load current user's wishlist ids if authenticated
        $wishlist = [];
        if (auth()->check()) {
            $wishlist = Wishlist::where('user_id', auth()->id())->pluck('barang_id')->toArray();
        }
        return view('user.shop', compact('produk','wishlist'));
    }

    /**
     * Show product detail for public (authenticated user)
     */
    // Use route-model binding to get Produk instance
    public function show(Produk $produk)
    {
        $produk->load(['harga', 'stok']);

        $wishlist = [];
        if (auth()->check()) {
            $wishlist = Wishlist::where('user_id', auth()->id())->pluck('barang_id')->toArray();
        }

        return view('user.product_detail', compact('produk', 'wishlist'));
    }
}
