<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'barang_id';
    
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'gambar',
        'deskripsi',
        'status'
    ];


    // public function harga()
    // {
    //     return $this->hasOne(harga::class, 'barang_id', 'barang_id');
    // }

    // Relationship with Stok
    public function stok()
    {
        return $this->hasOne(Stok::class, 'barang_id', 'barang_id');
    }

    // Relationship with StokIn
    public function stokIn()
    {
        return $this->hasMany(StokIn::class, 'barang_id', 'barang_id');
    }

    // Relationship with StokOut
    public function stokOut()
    {
        return $this->hasMany(StokOut::class, 'barang_id', 'barang_id');
    }

    // Relationship with Harga
    public function harga()
    {
        return $this->hasMany(Harga::class, 'barang_id', 'barang_id');
    }

    // Get current active price
    public function currentHarga()
    {
        return $this->hasOne(Harga::class, 'barang_id', 'barang_id')
                    ->where('status', 'aktif')
                    ->latest('created_at');
    }
}
