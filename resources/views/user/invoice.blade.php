@extends('layouts.user')
@section('title','Invoice #'.$order->id)
@section('content')
<style>
.invoice { max-width:800px; margin:0 auto; background:#fff; padding:24px; border-radius:12px; }
.invoice h2{ margin-bottom:8px }
.invoice .meta{ font-size:.9rem; color:#666 }
.invoice table{ width:100%; border-collapse:collapse; margin-top:12px }
.invoice table th, .invoice table td{ padding:8px 6px; border-bottom:1px solid #eee }
.invoice .total{ text-align:right; font-weight:700; font-size:1.1rem }
@media print{ .no-print{ display:none; } }
</style>
<div class="container py-4">
    <div class="invoice">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Invoice</h2>
            <div class="meta">#{{ $order->id }}<br>{{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</div>
        </div>
        <div class="mt-3">
            <div><strong>Pelanggan:</strong> {{ $order->user->name ?? 'N/A' }}</div>
            <div class="small text-muted">Email: {{ $order->user->email ?? '' }}</div>
        </div>

        <table class="mt-3">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $it)
                    @php
                        $p = $it->produk;
                        $price = $it->price ?? ($p->harga->where('status','aktif')->sortByDesc('created_at')->first()->harga_jual ?? 0);
                    @endphp
                    <tr>
                        <td>{{ $p->nama_barang ?? $it->barang_id }}</td>
                        <td class="text-center">{{ $it->quantity }}</td>
                        <td class="text-end">Rp {{ number_format($price,0,',','.') }}</td>
                        <td class="text-end">Rp {{ number_format(($price * $it->quantity),0,',','.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3 total">Total: Rp {{ number_format($order->total_amount,0,',','.') }}</div>

        <div class="mt-4 no-print d-flex gap-2">
            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">Kembali</a>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary">Pembayaran</a>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-success">Lihat Detail</a>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">Lihat Order</a>
            <a href="{{ route('orders.show', $order) }}?print=1" class="btn btn-secondary">Print</a>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-dark">Download PDF</a>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@if(!empty($autoPrint))
<script>window.print();</script>
@endif
@endsection
