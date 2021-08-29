<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masters', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->string('master_qr_code');
            $table->string('master_acc_id');
            $table->bigInteger("phone_number");
            $table->integer('source');
            $table->integer('destination');
            $table->integer('ticket_type');
            $table->integer('ticket_count');
            $table->double('total_fare');
            $table->dateTime('travel_date');
            $table->dateTime('master_expiry');
            $table->dateTime('grace_expiry');
            $table->dateTime('record_date');
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
        Schema::dropIfExists('masters');
    }
}
