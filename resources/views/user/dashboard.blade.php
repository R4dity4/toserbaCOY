@extends('layouts.user')
@section('title', 'Dashboard User')
@section('content')
@php
    use App\Models\Order;
    use App\Models\CartItem;
    $user = Auth::user();
    $totalOrders = Order::where('user_id', $user->id)->count();
    $cartCount = CartItem::where('user_id', $user->id)->sum('quantity') ?? CartItem::where('user_id', $user->id)->count();
    $joined = $user->created_at ? $user->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB' : '';
@endphp

<div class="container py-4">
    <div class="card mb-3" style="border-radius:14px;">
        <div class="card-body">
            <h4 class="card-title">Selamat datang, {{ $user->name }}!</h4>
            <p class="text-muted">Senang melihatmu kembali.</p>
            <div class="row text-center mt-3">
                <div class="col-4">
                    <div class="fw-bold">{{ $totalOrders }}</div>
                    <div class="text-muted small">Total Pesanan</div>
                </div>
                <div class="col-4">
                    <div class="fw-bold">{{ $cartCount }}</div>
                    <div class="text-muted small">Di Keranjang</div>
                </div>
                <div class="col-4">
                    <div class="fw-bold">{{ $joined }}</div>
                    <div class="text-muted small">Bergabung sejak</div>
                </div>
            </div>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <a href="{{ route('user.wishlist') }}" class="btn btn-outline-primary">Wishlist</a>
                <a href="{{ route('user.orders') }}" class="btn btn-primary">Riwayat Pesanan</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- quick links or other widgets could go here -->
    <div class="card" style="border-radius:14px;">
        <div class="card-body">
            <h5 class="card-title">Aktivitas Terbaru</h5>
            <p class="text-muted">Lihat riwayat pesananmu atau lanjutkan belanja.</p>
        </div>
    </div>
</div>
@endsection
