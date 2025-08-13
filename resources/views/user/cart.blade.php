@extends('layouts.user')
@section('title', 'Troli Belanja')
@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center" style="color: #ff6f00; font-weight: bold;">Troli Belanja</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="text-muted">Daftar barang yang akan dibeli akan muncul di sini.</p>
            <div class="text-center">
                <a href="#" class="btn btn-warning disabled">Checkout <i class="fas fa-credit-card"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection
