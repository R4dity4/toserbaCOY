@extends('layouts.user')
@section('title', 'Riwayat Pesanan')
@section('content')
{{-- Orders are provided by OrderController@index as $orders --}}

<div class="container py-4">
    <h4 class="mb-3">Riwayat Pesanan</h4>

    @forelse($orders as $order)
        <div class="card mb-3" style="border-radius:12px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="fw-semibold">Order ke-{{ $loop->iteration }} (ID: {{ $order->id }})</div>
                    <div class="text-muted small">{{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-{{ $order->status == 'paid' ? 'success' : 'warning' }} text-dark">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="fw-bold">Rp {{ number_format($order->total_amount,0,',','.') }}</div>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                </div>
            </div>
        </div>
    @empty
        <div class="card" style="border-radius:12px;">
            <div class="card-body text-center text-muted">
                Belum ada pesanan.
            </div>
        </div>
    @endforelse

</div>

@endsection
