<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukPromoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_promo', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('toko_id');
            $table->string('no_promo');
            $table->string('nama');
            $table->datetime('tanggal_mulai');
            $table->datetime('tanggal_berakhir');
            $table->enum('jenis', ['persentase', 'harga_tetap']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk_promo');
    }
}
