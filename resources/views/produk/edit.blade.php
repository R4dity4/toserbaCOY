@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-edit"></i> Edit Produk</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('produk.update', $produk->barang_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode_barang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" 
                                   id="kode_barang" name="kode_barang" value="{{ old('kode_barang', $produk->kode_barang) }}" required>
                            @error('kode_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" 
                                   id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $produk->nama_barang) }}" required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="elektronik" {{ old('kategori', $produk->kategori) == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                                <option value="pakaian" {{ old('kategori', $produk->kategori) == 'pakaian' ? 'selected' : '' }}>Pakaian</option>
                                <option value="makanan" {{ old('kategori', $produk->kategori) == 'makanan' ? 'selected' : '' }}>Makanan</option>
                                <option value="minuman" {{ old('kategori', $produk->kategori) == 'minuman' ? 'selected' : '' }}>Minuman</option>
                                <option value="peralatan_rumah" {{ old('kategori', $produk->kategori) == 'peralatan_rumah' ? 'selected' : '' }}>Peralatan Rumah</option>
                                <option value="kecantikan" {{ old('kategori', $produk->kategori) == 'kecantikan' ? 'selected' : '' }}>Kecantikan</option>
                                <option value="olahraga" {{ old('kategori', $produk->kategori) == 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                                <option value="buku" {{ old('kategori', $produk->kategori) == 'buku' ? 'selected' : '' }}>Buku</option>
                                <option value="mainan" {{ old('kategori', $produk->kategori) == 'mainan' ? 'selected' : '' }}>Mainan</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                            <select class="form-select @error('satuan') is-invalid @enderror" id="satuan" name="satuan" required>
                                <option value="">Pilih Satuan</option>
                                <option value="pcs" {{ old('satuan', $produk->satuan) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                <option value="kg" {{ old('satuan', $produk->satuan) == 'kg' ? 'selected' : '' }}>Kg</option>
                                <option value="gram" {{ old('satuan', $produk->satuan) == 'gram' ? 'selected' : '' }}>Gram</option>
                                <option value="liter" {{ old('satuan', $produk->satuan) == 'liter' ? 'selected' : '' }}>Liter</option>
                                <option value="ml" {{ old('satuan', $produk->satuan) == 'ml' ? 'selected' : '' }}>ML</option>
                                <option value="meter" {{ old('satuan', $produk->satuan) == 'meter' ? 'selected' : '' }}>Meter</option>
                                <option value="cm" {{ old('satuan', $produk->satuan) == 'cm' ? 'selected' : '' }}>CM</option>
                                <option value="box" {{ old('satuan', $produk->satuan) == 'box' ? 'selected' : '' }}>Box</option>
                                <option value="pack" {{ old('satuan', $produk->satuan) == 'pack' ? 'selected' : '' }}>Pack</option>
                                <option value="lusin" {{ old('satuan', $produk->satuan) == 'lusin' ? 'selected' : '' }}>Lusin</option>
                            </select>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga_beli" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('harga_beli') is-invalid @enderror" id="harga_beli" name="harga_beli" value="{{ old('harga_beli', optional($produk->harga->where('status','aktif')->sortByDesc('created_at')->first())->harga_beli) }}" required>
                            @error('harga_beli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="harga_jual" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('harga_jual') is-invalid @enderror" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', optional($produk->harga->where('status','aktif')->sortByDesc('created_at')->first())->harga_jual) }}" required>
                            @error('harga_jual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar Produk</label>
                        <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                               id="gambar" name="gambar" accept="image/*">
                        @error('gambar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: JPG, PNG, GIF. Max: 2MB</div>
                        @if($produk->gambar)
                            <div class="mt-2">
                                <small class="text-muted">Gambar saat ini:</small><br>
                                <img src="{{ asset($produk->gambar) }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="aktif" {{ old('status', $produk->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak_aktif" {{ old('status', $produk->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
