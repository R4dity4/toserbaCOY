@extends('layouts.guest')
@section('title', 'Register')
@section('content')
<div class="auth-wrapper">
    <div class="row justify-content-center w-100">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <div class="brand"><i class="fas fa-store"></i> Register</div>
                </div>
                <div class="card-body p-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Register</button>
                </form>
                @if(config('services.google.client_id'))
                <div class="text-center mt-3">
                    <p class="mb-3">atau</p>
                    <a href="{{ route('auth.google.redirect') }}" class="btn google-btn w-100">
                        <i class="fab fa-google"></i> <span>Daftar dengan Google</span>
                    </a>
                </div><br>
                @endif
            <div class="mt-3" style="text-align: center"> Sudah terdaftar? <a href=" {{ route ('login') }}"> Login di sini </a></div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
