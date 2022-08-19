<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aset', function (Blueprint $table) {
            $table->id();
            $table->string('kode_aset',20);
            $table->string('nama_aset');
            $table->string('merek',50)->nullable();
            $table->string('satuan',20)->nullable();
            $table->integer('jumlah');
            $table->string('kondisi',20);
            $table->text('deskripsi')->nullable();
            $table->text('gambar')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aset');
    }
}
