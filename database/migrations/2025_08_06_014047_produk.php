<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('barang_id');
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->enum('kategori', [
                'elektronik', 
                'pakaian', 
                'makanan', 
                'minuman', 
                'peralatan_rumah', 
                'kecantikan', 
                'olahraga', 
                'buku', 
                'mainan'
            ]);
            $table->enum('satuan', [
                'pcs', 
                'kg', 
                'gram', 
                'liter', 
                'ml', 
                'meter', 
                'cm', 
                'box', 
                'pack', 
                'lusin'
            ]);
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
