@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-eye"></i> Detail Produk</h5>
                <div>
                    <a href="{{ route('produk.edit', $produk->barang_id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('produk.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Kode Barang:</th>
                                <td>{{ $produk->kode_barang }}</td>
                            </tr>
                            <tr>
                                <th>Nama Barang:</th>
                                <td>{{ $produk->nama_barang }}</td>
                            </tr>
                            <tr>
                                <th>Kategori:</th>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($produk->kategori) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Satuan:</th>
                                <td>{{ strtoupper($produk->satuan) }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($produk->status == 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat:</th>
                                <td>{{ $produk->created_at }}</td>
                            </tr>
                            <tr>
                                <th>Diupdate:</th>
                                <td>{{ $produk->updated_at }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        @if($produk->gambar)
                            <div class="text-center">
                                <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->nama_barang }}" 
                                     class="img-fluid rounded" style="max-height: 300px;">
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-image fa-5x"></i>
                                <p>Tidak ada gambar</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($produk->deskripsi)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Deskripsi:</h6>
                        <p class="text-muted">{{ $produk->deskripsi }}</p>
                    </div>
                </div>
                @endif

                @if($produk->stok)
                <div class="row mt-4">
                    <div class="col-12">
                        <h6><i class="fas fa-warehouse"></i> Informasi Stok</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Jumlah Stok:</strong><br>
                                        <span class="h4">{{ $produk->stok->jumlah_stok }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Stok Minimum:</strong><br>
                                        <span class="text-warning">{{ $produk->stok->stok_minimum }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Stok Maksimum:</strong><br>
                                        <span class="text-info">{{ $produk->stok->stok_maksimum ?? 'Tidak dibatasi' }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Status Stok:</strong><br>
                                        @if($produk->stok->status_stok == 'habis')
                                            <span class="badge bg-danger">Habis</span>
                                        @else
                                            <span class="badge bg-success">Tersedia</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Price Information -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6><i class="fas fa-tag"></i> Informasi Harga</h6>
                        @if($produk->harga->count() > 0)
                            @php
                                $currentPrice = $produk->harga->where('status', 'aktif')->sortByDesc('created_at')->first();
                            @endphp
                            @if($currentPrice)
                                <div class="card bg-success text-white mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Harga Jual Aktif:</strong><br>
                                                <span class="h4">Rp {{ number_format($currentPrice->harga_jual, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Harga Beli:</strong><br>
                                                <span class="h5">Rp {{ number_format($currentPrice->harga_beli, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Margin:</strong><br>
                                                <span class="h5">
                                                    @if($currentPrice->margin)
                                                        {{ $currentPrice->margin }}%
                                                    @else
                                                        {{ number_format((($currentPrice->harga_jual - $currentPrice->harga_beli) / $currentPrice->harga_beli) * 100, 1) }}%
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <small>Dibuat pada: {{ $currentPrice->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                            @endif

                            <!-- Price History -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Riwayat Harga</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Dibuat Pada</th>
                                                    <th>Harga Beli</th>
                                                    <th>Harga Jual</th>
                                                    <th>Margin</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($produk->harga->sortByDesc('created_at') as $harga)
                                                <tr class="{{ $harga->status == 'aktif' ? 'table-success' : '' }}">
                                                    <td>{{ $harga->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>Rp {{ number_format($harga->harga_beli, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($harga->harga_jual, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if($harga->margin)
                                                            {{ $harga->margin }}%
                                                        @else
                                                            {{ number_format((($harga->harga_jual - $harga->harga_beli) / $harga->harga_beli) * 100, 1) }}%
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($harga->status == 'aktif')
                                                            <span class="badge bg-success">Aktif</span>
                                                        @else
                                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> Belum ada data harga untuk produk ini.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h6><i class="fas fa-history"></i> Riwayat Stok Terbaru</h6>
                        
                        @if($produk->stokIn->count() > 0)
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Stok Masuk Terakhir</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jumlah</th>
                                                <th>Supplier</th>
                                                <th>Total Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($produk->stokIn->take(5) as $stokIn)
                                            <tr>
                                                <td>{{ $stokIn->tanggal_masuk->format('d/m/Y') }}</td>
                                                <td>+{{ $stokIn->jumlah_masuk }}</td>
                                                <td>{{ $stokIn->supplier ?? '-' }}</td>
                                                <td>{{ $stokIn->total_harga ? 'Rp ' . number_format($stokIn->total_harga, 0, ',', '.') : '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($produk->stokOut->count() > 0)
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">Stok Keluar Terakhir</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jumlah</th>
                                                <th>Jenis</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($produk->stokOut->take(5) as $stokOut)
                                            <tr>
                                                <td>{{ $stokOut->tanggal_keluar->format('d/m/Y') }}</td>
                                                <td>-{{ $stokOut->jumlah_keluar }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ ucfirst($stokOut->jenis_keluar) }}</span>
                                                </td>
                                                <td>
                                                    @if($stokOut->status == 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($stokOut->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @else
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($produk->stokIn->count() == 0 && $produk->stokOut->count() == 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada riwayat stok untuk produk ini.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
