<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';
    protected $primaryKey = 'stok_id';
    
    protected $fillable = [
        'barang_id',
        'jumlah_stok',
        'stok_minimum',
        'stok_maksimum',
        'tanggal_masuk',
        'status_stok'
    ];

    // Relationship with Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'barang_id', 'barang_id');
    }
}
