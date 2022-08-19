<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->string('produk_id',10);
            $table->integer('jumlah');
            $table->string('harga');
            $table->string('harga_jual')->nullable();
            $table->string('status');
            $table->string('ref_id')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('status_data')->nullable();
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
        Schema::dropIfExists('stock');
    }
}
