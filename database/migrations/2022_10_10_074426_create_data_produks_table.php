<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_produks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk', 100);
            $table->string('ukuran', 20);
            $table->double('harga');
            $table->integer('stok');
            $table->longText('keterangan');
            $table->string('img');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_produks');
    }
};
