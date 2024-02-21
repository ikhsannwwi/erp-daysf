<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokOpnameGudangDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_opname_gudang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_opname_gudang_id');
            $table->foreignId('produk_id');
            $table->foreignId('satuan_id');
            $table->decimal('jumlah_stok_fisik', 32, 4);
            $table->decimal('selisih', 32, 4);
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('stok_opname_gudang_detail');
    }
}
