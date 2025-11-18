@extends('layouts.app')

@section('title', 'Stok Masuk')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-arrow-down"></i> Form Pemasukan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('stok.in.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="barang_id" class="form-label">Pilih Produk <span class="text-danger">*</span></label>
                        <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                            <option value="">Pilih Produk</option>
                            @foreach($produk as $item)
                            <option value="{{ $item->barang_id }}" {{ old('barang_id') == $item->barang_id ? 'selected' : '' }}>
                                {{ $item->kode_barang }} - {{ $item->nama_barang }}
                                @if($item->stok)
                                    (Stok: {{ $item->stok->jumlah_stok }})
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jumlah_masuk" class="form-label">Jumlah Beli <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('jumlah_masuk') is-invalid @enderror"
                                   id="jumlah_masuk" name="jumlah_masuk" value="{{ old('jumlah_masuk') }}" min="1" required>
                            @error('jumlah_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="harga_satuan" class="form-label">Harga Satuan</label>
                            <input type="number" class="form-control @error('harga_satuan') is-invalid @enderror"
                                   id="harga_satuan" name="harga_satuan" value="{{ old('harga_satuan') }}" step="0.01" min="0">
                            @error('harga_satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Beli <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                   id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="supplier" class="form-label">Supplier</label>
                            <input type="text" class="form-control @error('supplier') is-invalid @enderror"
                                   id="supplier" name="supplier" value="{{ old('supplier') }}">
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="total_harga" class="form-label">Total Harga</label>
                        <input type="number" class="form-control" id="total_harga" readonly>
                        <div class="form-text">Otomatis dihitung dari jumlah × harga satuan</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto calculate total harga
    function calculateTotal() {
        const jumlah = document.getElementById('jumlah_masuk').value || 0;
        const harga = document.getElementById('harga_satuan').value || 0;
        const total = jumlah * harga;
        document.getElementById('total_harga').value = total;
    }

    document.getElementById('jumlah_masuk').addEventListener('input', calculateTotal);
    document.getElementById('harga_satuan').addEventListener('input', calculateTotal);
</script>
@endsection
