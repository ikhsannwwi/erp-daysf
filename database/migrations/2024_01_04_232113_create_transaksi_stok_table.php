<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id'); // Menambahkan constrained() agar terhubung ke tabel produk
            $table->enum('metode_transaksi', ['masuk', 'keluar']); // Mengubah menjadi metode_transaksi
            $table->string('jenis_transaksi');
            $table->bigInteger('jumlah_unit');
            $table->string('created_by')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('transaksi_stok');
    }
}
