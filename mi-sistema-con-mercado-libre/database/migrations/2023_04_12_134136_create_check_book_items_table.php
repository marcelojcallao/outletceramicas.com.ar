<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckBookItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_book_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('check_book_id');
            $table->unsignedInteger('order_payment_id')->nullable();
            $table->integer('dates')->nullable();
            $table->date('pay_date')->nullable();
            $table->string('number')->nullable();
            $table->boolean('active')->default(true);
            $table->string('who')->nullable();
            $table->double('import', 14,2)->default(0);
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
        Schema::dropIfExists('check_book_items');
    }
}
