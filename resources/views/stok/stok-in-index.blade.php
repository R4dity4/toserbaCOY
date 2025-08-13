@extends('layouts.app')

@section('title', 'History Stok Masuk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-history"></i> History Pembelian</h2>
    <a href="{{ route('stok.in.form') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Beli
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($stokIn->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Jumlah Masuk</th>
                            <th>Harga Satuan</th>
                            <th>Total Harga</th>
                            <th>Supplier</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stokIn as $index => $item)
                        <tr>
                            <td>{{ $stokIn->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->produk)
                                        <img src="{{ asset('storage/' . $item->produk) }}" 
                                             alt="{{ $item->produk->nama_barang }}" 
                                             class="rounded me-2" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <strong>{{ $item->produk->nama_barang }}</strong><br>
                                        <small class="text-muted">{{ $item->produk->kode_barang }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success fs-6">
                                    +{{ $item->jumlah_masuk }} {{ $item->produk->satuan }}
                                </span>
                            </td>
                            <td>
                                @if($item->harga_satuan > 0)
                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->total_harga > 0)
                                    <strong>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                {{ $item->supplier ?? '-' }}
                            </td>
                            <td>
                                <small>{{ $item->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $stokIn->links() }}
            </div>

            <!-- Summary -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6><i class="fas fa-chart-bar"></i> Ringkasan</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h5 class="text-success">{{ $stokIn->total() }}</h5>
                                        <small class="text-muted">Total Transaksi</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h5 class="text-info">{{ $stokIn->sum('jumlah_masuk') }}</h5>
                                        <small class="text-muted">Total Item Beli</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h5 class="text-warning">Rp {{ number_format($stokIn->sum('total_harga'), 0, ',', '.') }}</h5>
                                        <small class="text-muted">Total Nilai</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h5 class="text-primary">{{ $stokIn->groupBy('barang_id')->count() }}</h5>
                                        <small class="text-muted">Produk Berbeda</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Data Stok Masuk</h5>
                <p class="text-muted">Silakan tambah stok masuk untuk melihat riwayat</p>
                <a href="{{ route('stok.in.form') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Stok Masuk
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
