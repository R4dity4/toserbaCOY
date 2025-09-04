@extends('layouts.user')
@section('title', 'Dashboard User')
@section('content')
<div class="container py-5">
    <h2>Selamat datang, {{ Auth::user()->name }}!</h2>
    <p>Berbelanjalah sesuai kebutuhan Anda.</p>
</div>
@endsection
