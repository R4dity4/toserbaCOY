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
        Schema::create('stok', function (Blueprint $table) {
            $table->id('stok_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('jumlah_stok')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->integer('stok_maksimum')->nullable();
            $table->date('tanggal_masuk')->default(now());
            // $table->date('tanggal_kadaluarsa')->nullable();
            // $table->string('lokasi_penyimpanan')->nullable();
            $table->enum('status_stok', ['tersedia', 'habis'])->default('tersedia');
            // $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('barang_id')->references('barang_id')->on('produk')->onDelete('cascade');
            
            // Index for faster queries
            $table->index(['barang_id', 'status_stok']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};
