<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukPromoDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_promo_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_promo_id');
            $table->foreignId('produk_id');
            $table->string('diskon');
            $table->string('total_stok_promo');
            $table->string('batas_pembelian');
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
        Schema::dropIfExists('produk_promo_detail');
    }
}
