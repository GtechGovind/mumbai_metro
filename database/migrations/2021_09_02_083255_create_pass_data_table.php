<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pass_data', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->string('phone_number');
            $table->string('master_qr_code');
            $table->integer('acc_id');
            $table->double('pass_price');
            $table->double('balance');
            $table->double('reg_fees');
            $table->integer('trips');
            $table->integer('operator_id');
            $table->dateTime('travel_date');
            $table->dateTime('master_expiry');
            $table->dateTime('grace_expiry');
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
        Schema::dropIfExists('pass_data');
    }
}
