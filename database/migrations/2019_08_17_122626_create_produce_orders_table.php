<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produce_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("user_id")->nullable();
            $table->dateTime('timestamp')->nullable();
            $table->string('reference_no')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('description')->nullable();
            $table->string('responsibility')->nullable();
            $table->integer('workshop_id')->nullable();
            $table->string('main_image')->nullable();
            $table->integer('deadline')->nullable();
            $table->date('max_date')->nullable();
            $table->integer('supply_cost')->nullable();
            $table->integer('manufacturing_cost')->nullable();
            $table->string('total_cost')->nullable();
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
        Schema::dropIfExists('produce_orders');
    }
}
