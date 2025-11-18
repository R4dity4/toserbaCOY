@extends('layouts.user')
@section('title','Wishlist')
@section('content')
<div class="container py-4">
    <h4 class="mb-3">Wishlist Anda</h4>
    <div class="row">
        @forelse($list as $w)
            @php $p = $w->produk; @endphp
            <div class="col-6 col-sm-4 col-md-3 mb-3">
                <div class="card" style="border-radius:12px;">
                    <img src="{{ asset($p->gambar ?? 'default.png') }}" class="card-img-top" style="height:140px;object-fit:cover;" alt="{{ $p->nama_barang }}">
                    <div class="card-body p-2">
                        <div class="fw-semibold">{{ $p->nama_barang }}</div>
                        <div class="small text-muted">Rp {{ number_format($p->harga->where('status','aktif')->sortByDesc('created_at')->first()->harga_jual ?? 0,0,',','.') }}</div>
                        <div class="mt-2 d-flex gap-2">
                            <a href="{{ route('user.shop') }}" class="btn btn-sm btn-outline-primary">Ke Toko</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted"><i class="fas fa-heart"></i> Ayo tambahkan wishlist.</div>
        @endforelse
    </div>
</div>
@endsection
