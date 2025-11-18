<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('barang_id');
            $table->timestamps();

            $table->index('user_id');
            $table->index('barang_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wishlists');
    }
};
