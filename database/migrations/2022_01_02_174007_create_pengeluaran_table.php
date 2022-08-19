<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengeluaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->string('tgl',20);
            $table->string('jenis_kas',2);
            $table->string('judul');
            $table->string('biaya',20);
            $table->string('deskripsi')->nullable();
            $table->string('persetujuan',10);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->softDeletes();  
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
        Schema::dropIfExists('pengeluaran');
    }
}
