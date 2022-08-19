<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_trans')->unique();
            $table->string('tgl_trans',20);
            $table->string('pelanggan_id',10);
            $table->string('totalModal',20)->nullable();
            $table->string('totalBiaya',20)->nullable();
            $table->string('subtotal',20);
            $table->string('ppn',10)->nullable();
            $table->string('pph',10)->nullable();
            $table->string('total',20);
            $table->string('deskripsi')->nullable();
            $table->string('status_trans',20);
            $table->integer('jenis_trans');
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
        Schema::dropIfExists('transaksi');
    }
}
