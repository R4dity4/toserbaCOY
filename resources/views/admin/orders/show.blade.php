@extends('layouts.app')
@section('title', 'Order Detail')
@section('content')
<div class="container py-5">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light mb-3">← Back to orders</a>

    <div class="card mb-3">
        <div class="card-body">
            <h4>Order #{{ $order->id }}</h4>
            <p class="mb-1"><strong>User:</strong> {{ optional($order->user)->name }} ({{ optional($order->user)->email }})</p>
            <p class="mb-1"><strong>Created:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p class="mb-0"><strong>Status:</strong> {{ $order->status ?? '—' }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Items</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ optional($item->produk)->nama ?? '—' }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format(($item->harga * $item->jumlah), 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5>Total: Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</h5>
            @if($order->payments && $order->payments->count())
                <hr>
                <h6>Payments</h6>
                <ul>
                    @foreach($order->payments as $payment)
                        <li>{{ $payment->provider ?? '—' }} — {{ $payment->status ?? '—' }} — Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
