<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRemitoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remito_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('remito_id')->unsigned()->nullable();
            $table->float('quantity',10,2)->unsigned()->nullable();
            $table->string('product_id')->nullable();
            $table->float('neto',10,2)->nullable()->default(0);
            $table->float('iva',10,2)->nullable()->default(0);
            $table->float('total',10,2)->nullable()->default(0);
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
        Schema::dropIfExists('remito_items');
    }
}
