<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTransaksiStokIdToPenyesuaianStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penyesuaian_stok', function (Blueprint $table) {
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
        Schema::table('penyesuaian_stok', function (Blueprint $table) {
            //
        });
    }
}
