<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXeninvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xeninvoice', function (Blueprint $table) {
            $table->id();
            $table->integer('transaksi_id');
            $table->string('xen_id');
            $table->string('xen_user_id');
            $table->string('xen_external_id');
            $table->string('xen_status');
            $table->text('xen_invoice_url');
            $table->string('xen_expiry_date');
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
        Schema::dropIfExists('xeninvoice');
    }
}
