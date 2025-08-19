<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokOut extends Model
{
    protected $table = 'stok_out';
    protected $primaryKey = 'stok_out_id';
    
    protected $fillable = [
        'barang_id',
        'jumlah_keluar',
        'tanggal_keluar',
        'jenis_keluar',
        'status'
    ];

    protected $casts = [
        'tanggal_keluar' => 'date'
    ];

    // Relationship with Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'barang_id', 'barang_id');
    }
}
