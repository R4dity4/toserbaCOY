@extends('layouts.guest')
@section('title', 'Login')
@section('content')
<div class="auth-wrapper">
    <div class="row justify-content-center w-100">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <div class="brand"><i class="fas fa-store"></i> Login</div>
                </div>
                <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                @if(config('services.google.client_id'))
                <div class="text-center mt-3">
                    <p class="mb-3">atau</p>
                    <a href="{{ route('auth.google.redirect') }}" class="btn google-btn w-100">
                        <i class="fab fa-google"></i> <span>Masuk dengan Google</span>
                    </a>
                </div>
                @endif
                <div class="mt-3" style="text-align: center; display: flex; justify-content: center;">Belum punya akun? <a href=" {{ route ('register') }}">  Daftar Sekarang </a>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
