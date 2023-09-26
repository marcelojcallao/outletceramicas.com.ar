<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosClientesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_clientes_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pedido_cliente_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('pricelist_id')->unsigned()->nullable();
            $table->float('unit_price',10,2)->nullable()->default(0);
            $table->float('quantity',10,2)->unsigned()->nullable()->default(1);
            $table->float('discount_percentage',10,2)->unsigned()->nullable()->default(0);
            $table->float('discount_import',10,2)->nullable()->default(0);
            $table->integer('iva_id')->unsigned()->nullable();
            $table->float('iva_percentage',10,2)->nullable()->default(0);
            $table->float('iva_import',10,2)->nullable()->default(0);
            $table->float('neto_import',10,2)->nullable()->default(0);
            $table->float('total',10,2)->nullable()->default(0);
            $table->json('price_list')->nullable();
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
        Schema::dropIfExists('pedidos_clientes_items');
    }
}
