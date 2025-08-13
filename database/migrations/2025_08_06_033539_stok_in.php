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
        Schema::create('stok_in', function (Blueprint $table) {
            $table->id('stok_in_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('jumlah_masuk');
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('total_harga', 15, 2)->nullable();
            $table->date('tanggal_masuk');
            $table->string('supplier')->nullable();
            // $table->string('nomor_dokumen')->nullable(); // invoice/receipt number
            // $table->text('keterangan')->nullable();
            // $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            // $table->string('user_input')->nullable(); // who input the data
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('barang_id')->references('barang_id')->on('produk')->onDelete('cascade');
            
            // Index for faster queries
            $table->index(['barang_id', 'tanggal_masuk']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_in');
    }
};
