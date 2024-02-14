<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id');
            $table->foreignId('gudang_id');
            $table->foreignId('formula_id');
            $table->foreignId('transaksi_stok_id');
            $table->date('tanggal');
            $table->string('no_produksi');
            $table->bigInteger('jumlah_unit');
            $table->text('keteragan');
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
        Schema::dropIfExists('produksi');
    }
}
