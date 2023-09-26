<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_invoice_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->float('quantity',10,2)->unsigned()->nullable();
            $table->float('neto_import',10,2)->nullable();
            $table->float('iva_import',10,2)->nullable();
            $table->integer('iva_id')->unsigned()->nullable();
            $table->integer('discount')->unsigned()->nullable();
            $table->float('discount_import',10,2)->nullable();
            $table->float('total',10,2)->nullable();
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
        Schema::dropIfExists('sale_invoice_items');
    }
}





