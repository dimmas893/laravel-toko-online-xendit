<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website', function (Blueprint $table) {
            $table->id();
            $table->String('nama_website')->nullable();
            $table->String('tagline')->nullable();
            $table->String('contact')->nullable();
            $table->integer('provinsi')->nullable();
            $table->integer('kota')->nullable();
            $table->integer('kecamatan')->nullable();
            $table->integer('kode_pos')->nullable();
            $table->text('address')->nullable();
            $table->text('icon')->nullable();
            $table->text('description')->nullable();
            $table->integer('trx_ppn')->nullable();
            $table->integer('trx_pph')->nullable();
            $table->integer('trx_markup')->nullable();
            $table->integer('trx_verifikasi')->nullable();
            $table->string('trx_duration_online')->nullable();
            $table->string('trx_duration_offline')->nullable();   
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
        Schema::dropIfExists('website');
    }
}
