<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\StokOut;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $items = CartItem::with(['produk.harga' => function($q){ $q->where('status','aktif')->latest(); }])
            ->where('user_id', Auth::id())
            ->get();
        return view('user.cart', compact('items'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:produk,barang_id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $userId = Auth::id();
        $barangId = $request->barang_id;
        $qty = $request->input('quantity', 1);

        $item = CartItem::where('user_id',$userId)->where('barang_id',$barangId)->first();
        if ($item) {
            $item->increment('quantity', $qty);
        } else {
            CartItem::create([
                'user_id' => $userId,
                'barang_id' => $barangId,
                'quantity' => $qty,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Produk ditambahkan ke cart']);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorizeOwnership($cartItem);
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cartItem->update(['quantity' => $request->quantity]);
        return response()->json(['success'=>true]);
    }

    public function remove(CartItem $cartItem)
    {
        $this->authorizeOwnership($cartItem);
        $cartItem->delete();
        return response()->json(['success'=>true]);
    }

    protected function authorizeOwnership(CartItem $item): void
    {
        abort_unless($item->user_id === Auth::id(), 403);
    }

    public function checkout(Request $request)
    {
        $userId = Auth::id();
        $items = CartItem::with('produk.stok')->where('user_id', $userId)->get();
        if ($items->isEmpty()) {
            return response()->json(['success'=>false, 'message'=>'Cart kosong']);
        }

        // Validasi stok cukup
        foreach ($items as $it) {
            $available = $it->produk->stok->jumlah_stok ?? 0;
            if ($available < $it->quantity) {
                return response()->json([
                    'success'=>false,
                    'message'=>'Stok tidak cukup untuk '+$it->produk->nama_barang
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            foreach ($items as $it) {
                StokOut::create([
                    'barang_id' => $it->barang_id,
                    'jumlah_keluar' => $it->quantity,
                    'tanggal_keluar' => now()->toDateString(),
                    'jenis_keluar' => 'penjualan',
                    'status' => 'approved'
                ]);
            }
            CartItem::where('user_id',$userId)->delete();
            DB::commit();
            return response()->json(['success'=>true, 'message'=>'Checkout berhasil']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success'=>false, 'message'=>'Checkout gagal','error'=>$e->getMessage()], 500);
        }
    }
}
