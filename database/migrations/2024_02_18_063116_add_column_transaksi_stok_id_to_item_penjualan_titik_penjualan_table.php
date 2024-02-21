<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTransaksiStokIdToItemPenjualanTitikPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_penjualan_titik_penjualan', function (Blueprint $table) {
            $table->foreignId('transaksi_stok_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_penjualan_titik_penjualan', function (Blueprint $table) {
            //
        });
    }
}
