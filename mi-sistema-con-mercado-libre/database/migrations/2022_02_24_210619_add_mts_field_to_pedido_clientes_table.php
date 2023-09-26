<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMtsFieldToPedidoClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos_clientes_items', function (Blueprint $table) {
            $table->double('real_mts', 12, 2)->nullable();
            $table->double('mts_to_invoiced', 12, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos_clientes_items', function (Blueprint $table) {
            //
        });
    }
}
