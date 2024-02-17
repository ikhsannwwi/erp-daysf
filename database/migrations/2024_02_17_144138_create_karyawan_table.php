<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departemen_id');
            $table->string('kode');
            $table->string('nama_depan');
            $table->string('nama_belakang')->nullable();
            $table->string('nama');
            $table->string('email');
            $table->string('telepon');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('jabatan');
            $table->date('tanggal_bergabung');
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
        Schema::dropIfExists('karyawan');
    }
}
