<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMigrasiIdToPenyesuaianStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penyesuaian_stok', function (Blueprint $table) {
            $table->foreignId('migrasi_id')->nullable();
            $table->foreignId('migrasi_transaksi_stok_id')->nullable();
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
