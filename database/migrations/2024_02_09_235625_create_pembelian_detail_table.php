<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembelianDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id');
            $table->foreignId('produk_id');
            $table->foreignId('gudang_id');
            $table->foreignId('transaksi_stok_id');
            $table->bigInteger('jumlah_unit');
            $table->bigInteger('harga_satuan');
            $table->bigInteger('sub_total');
            $table->text('keterangan');
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
        Schema::dropIfExists('pembelian_detail');
    }
}
