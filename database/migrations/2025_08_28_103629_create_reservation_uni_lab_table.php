<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationUniLabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_uni_lab', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('fac_id');
            $table->unsignedBigInteger('uni_id');
            $table->unsignedBigInteger('lab_id');
            $table->string('visitor_phone');
            $table->unsignedBigInteger('service_id');
            $table->integer('samples');
            $table->string('status', 20)->default('valid');
            $table->date('date');
            $table->time('time');
            $table->enum('confirmation', ['Pending', 'Confirmed'])->default('Pending');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('device_id')->references('id')->on('uni_devices')->onDelete('cascade');
            $table->foreign('lab_id')->references('id')->on('uni_labs')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_uni_lab');
    }
}
