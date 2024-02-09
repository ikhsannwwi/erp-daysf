<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeranjangStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penyesuaian_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gudang_id');
            $table->foreignId('produk_id');
            $table->date('tanggal');
            $table->enum('metode_transaksi', ['masuk', 'keluar']); // Mengubah menjadi metode_transaksi
            $table->string('jenis_transaksi');
            $table->bigInteger('jumlah_unit');
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
        Schema::dropIfExists('penyesuaian_stok');
    }
}
