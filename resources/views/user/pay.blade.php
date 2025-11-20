@extends('layouts.user')
@section('title', 'Pembayaran Order #'.$order->id)
@section('content')
<div class="container py-4">
    <h2 class="mb-3">Pembayaran Order #{{ $order->id }}</h2>

    <div class="row">
        <div class="col-lg-8 mb-3">
            <div class="card shadow-sm">
                <div class="card-header">Ringkasan Order</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $total=0; @endphp
                        @foreach($order->items as $it)
                            @php $sub = $it->price * $it->quantity; $total += $sub; @endphp
                            <tr>
                                <td>{{ $it->produk->nama_barang ?? 'Produk #'.$it->barang_id }}</td>
                                <td class="text-center">{{ $it->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($it->price,0,',','.') }}</td>
                                <td class="text-end">Rp {{ number_format($sub,0,',','.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-end">Rp {{ number_format($total,0,',','.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">Instruksi Pembayaran</div>
                <div class="card-body">
                    <p>Status: <span class="badge bg-{{ $order->status==='paid'?'success':'warning' }}">{{ strtoupper($order->status) }}</span></p>
                    <div class="mb-3">
                        <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="btn btn-outline-secondary w-100 mb-2">Lihat Invoice</a>
                        <a href="{{ route('orders.invoice.download', $order) }}" target="_blank" class="btn btn-primary w-100">Unduh Invoice (PDF)</a>
                    </div>
                    @if($order->status !== 'paid')
                        <p>Silakan transfer ke rekening berikut:</p>
                        <ul>
                            <li>Bank: BCA</li>
                            <li>No. Rekening: 1234567890</li>
                            <li>Atas Nama: Toserba COY</li>
                            <li>Total: <strong>Rp {{ number_format($order->total_amount,0,',','.') }}</strong></li>
                        </ul>
                        <form method="POST" action="{{ route('orders.paid', $order) }}">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">Metode</label>
                                <select name="method" class="form-select">
                                    <option value="manual_transfer">Manual Transfer</option>
                                    <option value="cod">COD</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan/Referensi</label>
                                <input type="text" name="reference" class="form-control" placeholder="No. transaksi, dsb (opsional)">
                            </div>
                            <button class="btn btn-success w-100">Tandai Sudah Bayar</button>
                        </form>
                    @else
                        <p>Terima kasih! Pembayaran Anda sudah kami terima.</p>
                        <a class="btn btn-primary w-100" href="{{ route('user.shop') }}">Belanja Lagi</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
