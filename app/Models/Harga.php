<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    protected $table = 'harga';
    protected $primaryKey = 'harga_id';
    
    protected $fillable = [
        'barang_id',
        'harga_beli',
        'harga_jual',
        'margin',
        'status'
    ];

    protected $casts = [
        // 'tanggal_berlaku' => 'date',
        // 'tanggal_berakhir' => 'date'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'barang_id', 'barang_id');
    }
}
