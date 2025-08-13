@extends('layouts.app')

@section('title', 'Stok Keluar')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-arrow-up"></i> Form Penjualan</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('stok.out.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="barang_id" class="form-label">Pilih Produk <span class="text-danger">*</span></label>
                        <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                            <option value="">Pilih Produk</option>
                            @foreach($produk as $item)
                            <option value="{{ $item->barang_id }}" data-stok="{{ $item->stok->jumlah_stok ?? 0 }}" 
                                    {{ old('barang_id') == $item->barang_id ? 'selected' : '' }}>
                                {{ $item->kode_barang }} - {{ $item->nama_barang }}
                                (Stok: {{ $item->stok->jumlah_stok ?? 0 }})
                            </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jumlah_keluar" class="form-label">Jumlah Jual <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('jumlah_keluar') is-invalid @enderror" 
                                   id="jumlah_keluar" name="jumlah_keluar" value="{{ old('jumlah_keluar') }}" min="1" required>
                            @error('jumlah_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text" id="stok-info">Pilih produk untuk melihat stok tersedia</div>
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
                            <label for="tanggal_keluar" class="form-label">Tanggal Jual <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_keluar') is-invalid @enderror" 
                                   id="tanggal_keluar" name="tanggal_keluar" value="{{ old('tanggal_keluar', date('Y-m-d')) }}" required>
                            @error('tanggal_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="jenis_keluar" class="form-label">Jenis <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_keluar') is-invalid @enderror" id="jenis_keluar" name="jenis_keluar" required>
                                <option value="">Pilih Jenis</option>
                                <option value="penjualan" {{ old('jenis_keluar') == 'penjualan' ? 'selected' : '' }}>Penjualan</option>
                                <option value="retur" {{ old('jenis_keluar') == 'retur' ? 'selected' : '' }}>Retur</option>
                                <option value="rusak" {{ old('jenis_keluar') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="hilang" {{ old('jenis_keluar') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                <option value="transfer" {{ old('jenis_keluar') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            </select>
                            @error('jenis_keluar')
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
                        <button type="submit" class="btn btn-danger">
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
    // Show available stock when product is selected
    document.getElementById('barang_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stok = selectedOption.getAttribute('data-stok') || 0;
        const stokInfo = document.getElementById('stok-info');
        
        if (this.value) {
            stokInfo.textContent = `Stok tersedia: ${stok}`;
            stokInfo.className = stok > 0 ? 'form-text text-success' : 'form-text text-danger';
            
            // Set max value for jumlah_keluar
            document.getElementById('jumlah_keluar').setAttribute('max', stok);
        } else {
            stokInfo.textContent = 'Pilih produk untuk melihat stok tersedia';
            stokInfo.className = 'form-text';
            document.getElementById('jumlah_keluar').removeAttribute('max');
        }
    });

    // Auto calculate total harga
    function calculateTotal() {
        const jumlah = document.getElementById('jumlah_keluar').value || 0;
        const harga = document.getElementById('harga_satuan').value || 0;
        const total = jumlah * harga;
        document.getElementById('total_harga').value = total;
    }

    document.getElementById('jumlah_keluar').addEventListener('input', calculateTotal);
    document.getElementById('harga_satuan').addEventListener('input', calculateTotal);
</script>
@endsection
