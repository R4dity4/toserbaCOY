@extends('layouts.app')

@section('title', 'History Stok Keluar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-history"></i> History Penjualan</h2>
    <a href="{{ route('stok.out.form') }}" class="btn btn-danger">
        <i class="fas fa-plus"></i> Jual
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($stokOut->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Jumlah Keluar</th>
                            <th>Harga Satuan</th>
                            <th>Total Harga</th>
                            <th>Jenis Keluar</th>
                            <th>Keterangan</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stokOut as $index => $item)
                        <tr>
                            <td>{{ $stokOut->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->produk->gambar)
                                        <img src="{{ asset('storage/' . $item->produk->gambar) }}" 
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
                                <span class="badge bg-danger fs-6">
                                    -{{ $item->jumlah_keluar }} {{ $item->produk->satuan }}
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
                                @php
                                    $badgeClass = match($item->jenis_keluar) {
                                        'penjualan' => 'bg-success',
                                        'retur' => 'bg-warning',
                                        'rusak' => 'bg-danger',
                                        'hilang' => 'bg-dark',
                                        'transfer' => 'bg-info',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($item->jenis_keluar) }}
                                </span>
                            </td>
                            <td>
                                {{ $item->keterangan ?? '-' }}
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
                {{ $stokOut->links() }}
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
                                        <h5 class="text-danger">{{ $stokOut->total() }}</h5>
                                        <small class="text-muted">Total Transaksi</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h5 class="text-info">{{ $stokOut->sum('jumlah_keluar') }}</h5>
                                        <small class="text-muted">Total Item Keluar</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h5 class="text-warning">Rp {{ number_format($stokOut->sum('total_harga'), 0, ',', '.') }}</h5>
                                        <small class="text-muted">Total Nilai</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h5 class="text-primary">{{ $stokOut->groupBy('barang_id')->count() }}</h5>
                                        <small class="text-muted">Produk Berbeda</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Breakdown by Type -->
                            <hr>
                            <h6 class="mt-3"><i class="fas fa-pie-chart"></i> Breakdown Jenis Keluar</h6>
                            <div class="row">
                                @php
                                    $jenisBreakdown = $stokOut->groupBy('jenis_keluar');
                                @endphp
                                @foreach($jenisBreakdown as $jenis => $items)
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <h6 class="text-primary">{{ $items->count() }}</h6>
                                        <small class="text-muted">{{ ucfirst($jenis) }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Data Stok Keluar</h5>
                <p class="text-muted">Silakan tambah stok keluar untuk melihat riwayat</p>
                <a href="{{ route('stok.out.form') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Stok Keluar
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
