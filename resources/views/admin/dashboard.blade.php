@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<div class="container py-5">
    {{-- <h2>Selamat datang, Admin!</h2>
    <p>Semua fitur manajemen produk dan stok tersedia di sini.</p> --}}
    <div class="container py-4">
        <h1 class="mb-4">Admin Dashboard</h1>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Produk</h5>
                        <p class="card-text display-4">{{ $totalProduk }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total User (Termasuk Admin)</h5>
                        <p class="card-text display-4">{{ $totalUser }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Stok</h5>
                        <p class="card-text display-4">{{ $totalStok }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">Info</div>
            <div class="card-body">
                <p>Silakan kelola data produk, user, dan stok melalui menu di atas.</p>
            </div>
        </div>
    </div>
@endsection
