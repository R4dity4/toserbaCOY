<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stok_out', function (Blueprint $table) {
            $table->id('stok_out_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('jumlah_keluar');
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('total_harga', 15, 2)->nullable();
            $table->date('tanggal_keluar');
            // $table->string('tujuan')->nullable(); // customer/department
            // $table->string('nomor_dokumen')->nullable(); // sales invoice/delivery note
            $table->enum('jenis_keluar', ['penjualan', 'retur', 'rusak', 'hilang', 'transfer'])->default('penjualan');
            // $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            // $table->string('nama_penginput')->nullable(); // who input the data
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('barang_id')->references('barang_id')->on('produk')->onDelete('cascade');
            
            // Index for faster queries
            $table->index(['barang_id', 'tanggal_keluar']);
            $table->index(['jenis_keluar', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_out');
    }
};
