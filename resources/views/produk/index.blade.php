@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-box"></i> Daftar Produk</h2>
            <a href="{{ route('produk.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th>Harga Jual</th>
                                <th>Status Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produk as $item)
                            <tr>
                                <td>{{ $item->kode_barang }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($item->kategori) }}</span>
                                </td>
                                <td>{{ $item->satuan }}</td>
                                <td>
                                    @if($item->stok)
                                        {{ $item->stok->jumlah_stok }}
                                    @else
                                        <span class="text-muted">gaada info le</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->harga->count() > 0)
                                        @php
                                            $currentPrice = $item->harga->where('status', 'aktif')->sortByDesc('created_at')->first();
                                        @endphp
                                        @if($currentPrice)
                                            <strong class="text-success">Rp {{ number_format($currentPrice->harga_jual, 0, ',', '.') }}</strong><br>
                                            <small class="text-muted">Beli: Rp {{ number_format($currentPrice->harga_beli, 0, ',', '.') }}</small>
                                        @else
                                            <span class="text-muted">Belum ada harga</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Belum ada harga</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->stok)
                                        @if($item->stok->jumlah_stok == 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @elseif($item->stok->jumlah_stok > 0)
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-secondary">No Data</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">No Data</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status == 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group  gap-3" role="group">
                                        <a href="{{ route('produk.show', $item->barang_id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('produk.edit', $item->barang_id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('produk.destroy', $item->barang_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada produk</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
