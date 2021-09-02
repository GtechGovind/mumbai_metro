<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->id();
            $table->string("order_no")->unique();
            $table->bigInteger('phone_number');
            $table->string("pg_order_id")->nullable();
            $table->integer("source");
            $table->integer("destination");
            $table->integer("ticket_count");
            $table->integer("ticket_type");
            $table->double("total_fare");
            $table->integer("pg_id");
            $table->integer("order_status");
            $table->integer("order_flag");
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
        Schema::dropIfExists('sale_orders');
    }
}
