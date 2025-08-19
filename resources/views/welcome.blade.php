@extends('layouts.guest')

@section('title', 'Selamat Datang - Sistem Inventory Toserba')

@section('content')
<style>
    .video-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        object-fit: cover;
    }

    .content-overlay {
        position: relative;
        z-index: 1;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        text-align: center;
        margin-top: 10%;
    }

    .welcome-text {
        text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
    }
</style>

<!-- Video Background -->
<video autoplay muted loop class="video-background">
    <source src="images/bg.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<!-- Content Overlay -->
<div class="text-center">
    <h1 class="text-warning welcome-text" style="font-size: 3rem; margin-bottom: 1rem;">
        SELAMAT DATANG DI DALAM TOSERBA
    </h1>
    <h3 class="welcome-text" style="font-size: 1.5rem; margin-bottom: 2rem;">
        Silahkan anggap rumah sendiri 😀
    </h3>

    <!-- <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('produk.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-box"></i> Kelola Produk
                </a>
                <a href="{{ route('stok.in.form') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-arrow-down"></i> Stok Masuk
                </a>
                <a href="{{ route('stok.out.form') }}" class="btn btn-danger btn-lg">
                    <i class="fas fa-arrow-up"></i> Stok Keluar
                </a>
                <a href="{{ route('stok.riwayat') }}" class="btn btn-info btn-lg">
                    <i class="fas fa-history"></i> Riwayat Stok
                </a>
            </div>
        </div>
    </div> -->
</div>

@endsection
