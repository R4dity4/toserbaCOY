<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokIn extends Model
{
    protected $table = 'stok_in';
    protected $primaryKey = 'stok_in_id';
    
    protected $fillable = [
        'barang_id',
        'jumlah_masuk',
        'harga_satuan',
        'total_harga',
        'tanggal_masuk',
        'supplier'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date'
    ];

    // Relationship with Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'barang_id', 'barang_id');
    }
}
