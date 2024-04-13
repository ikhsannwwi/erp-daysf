<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCoumnMetodeTransaksiToPenyesuaianStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penyesuaian_stok', function (Blueprint $table) {
            DB::statement("ALTER TABLE penyesuaian_stok MODIFY COLUMN metode_transaksi ENUM('masuk', 'keluar', 'migrasi_gudang', 'migrasi_ke_toko')");
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
            DB::statement("ALTER TABLE penyesuaian_stok MODIFY COLUMN metode_transaksi VARCHAR(255)");
        });
    }
}
