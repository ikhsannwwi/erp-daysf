<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemabayaranTransaksiPenjualanTitikPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_transaksi_penjualan_titik_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id');
            $table->decimal('nominal_pembayaran', 32, 2);
            $table->decimal('nominal_kembalian', 32, 2);
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
        Schema::dropIfExists('pembayaran_transaksi_penjualan_titik_penjualan');
    }
}
