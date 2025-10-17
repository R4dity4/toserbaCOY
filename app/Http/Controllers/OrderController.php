<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\StokOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load(['items.produk.harga','payments']);
        return view('user.pay', compact('order'));
    }

    public function markPaid(Request $request, Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        if ($order->status === 'paid') {
            return redirect()->route('orders.show', $order)->with('success','Order sudah dibayar');
        }

        DB::beginTransaction();
        try {
            // create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'method' => $request->input('method', 'manual_transfer'),
                'status' => 'paid',
                'amount' => $order->total_amount,
                'reference' => $request->input('reference')
            ]);

            // create stok_out records per item
            foreach ($order->items as $it) {
                StokOut::create([
                    'barang_id' => $it->barang_id,
                    'jumlah_keluar' => $it->quantity,
                    'tanggal_keluar' => now()->toDateString(),
                    'jenis_keluar' => 'penjualan',
                    'status' => 'approved'
                ]);
            }

            $order->update(['status' => 'paid']);
            DB::commit();
            return redirect()->route('orders.show', $order)->with('success','Pembayaran berhasil, order ditandai lunas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('orders.show', $order)->with('error','Gagal memproses pembayaran: '.$e->getMessage());
        }
    }
}
