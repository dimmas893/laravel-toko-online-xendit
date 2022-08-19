<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk',20);
            $table->string('nama_produk');
            $table->string('merek',100)->nullable();
            $table->string('slug');
            // $table->integer('kategori_id');
            // $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('kategori_id')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->string('satuan',20)->nullable();
            $table->string('harga',20);
            $table->string('harga_jual',20)->nullable();
            $table->integer('jumlah');
            $table->integer('berat');
            $table->text('deskripsi')->nullable();
            $table->text('gambar_utama');
            $table->string('jenis_jual',10);
            $table->integer('dilihat')->nullable();
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
        Schema::dropIfExists('produk');
    }
}
