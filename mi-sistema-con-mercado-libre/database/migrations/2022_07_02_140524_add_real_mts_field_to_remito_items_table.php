<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRealMtsFieldToRemitoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remito_items', function (Blueprint $table) {
            $table->double('real_mts', 15, 2)->nullable();
            $table->double('rounded_mts', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remito_items', function (Blueprint $table) {
            //
        });
    }
}
