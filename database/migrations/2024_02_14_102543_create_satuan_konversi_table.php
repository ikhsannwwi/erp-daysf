<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatuanKonversiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('satuan_konversi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id');
            $table->foreignId('satuan_id');
            $table->decimal('kuantitas_satuan', 32, 4);
            $table->string('nama_konversi');
            $table->decimal('kuantitas_konversi', 32, 4);
            $table->boolean('status');
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
        Schema::dropIfExists('satuan_konversi');
    }
}
