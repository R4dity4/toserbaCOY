<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate(['barang_id' => 'required']);
        $userId = Auth::id();
        $barangId = $request->input('barang_id');

        $exists = Wishlist::where('user_id', $userId)->where('barang_id', $barangId)->first();
        if ($exists) {
            $exists->delete();
            return response()->json(['success' => true, 'action' => 'removed']);
        }

        Wishlist::create(['user_id' => $userId, 'barang_id' => $barangId]);
        return response()->json(['success' => true, 'action' => 'added']);
    }

    public function index()
    {
        $list = Wishlist::with('produk')->where('user_id', Auth::id())->get();
        return view('user.wishlist', compact('list'));
    }
}
