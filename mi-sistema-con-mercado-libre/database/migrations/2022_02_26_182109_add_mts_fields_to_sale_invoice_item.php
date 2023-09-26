<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMtsFieldsToSaleInvoiceItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_invoice_items', function (Blueprint $table) {
            $table->double('real_mts', 12, 2)->nullable();
            $table->double('mts_to_invoiced', 12, 2)->nullable();
            $table->double('mts_by_unity', 12, 2)->nullable();
            $table->boolean('isCHP')->default(false)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_invoice_items', function (Blueprint $table) {
            //
        });
    }
}
