@extends('layouts.user')
@section('title', 'ToserbaCOY Shop')
@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center" style="color: #ff6f00; font-weight: bold;">ToserbaCOY Shop</h2>
    <div class="row justify-content-center">
        @forelse($produk as $item)
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #ff9800 80%, #fff3e0 100%);">
                <img src="{{ asset( $item->gambar ?? 'default.png') }}" class="card-img-top" alt="{{ $item->nama_barang }}" style="height:180px; object-fit:cover;">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color:#ff6f00; font-weight:bold;">{{ $item->nama_barang }}</h5>
                    <p class="card-text mb-1"><span class="badge bg-warning text-dark">Rp {{ number_format($item->harga->where('status','aktif')->sortByDesc('created_at')->first()->harga_jual ?? 0, 0, ',', '.') }}</span></p>
                    <button class="btn btn-outline-warning w-100 add-to-cart" data-id="{{ $item->barang_id }}" style="font-weight:bold;">Tambah ke Cart <i class="fas fa-cart-plus"></i></button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <p class="text-muted">Belum ada produk tersedia.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.querySelectorAll('.add-to-cart').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // TODO: AJAX add to cart
            alert('Barang ditambahkan ke cart!');
        });
    });
</script>
@endsection
