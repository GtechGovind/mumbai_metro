<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_data', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->string('master_qr_code');
            $table->string('slave_qr_code');
            $table->string('slave_acc_id');
            $table->bigInteger('phone_number');
            $table->integer('source');
            $table->integer('destination');
            $table->integer('ticket_type');
            $table->string('qr_direction');
            $table->text('qr_code_data');
            $table->string('qr_status');
            $table->dateTime('record_date');
            $table->dateTime('slave_expiry_date');
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
        Schema::dropIfExists('qr_data');
    }
}
