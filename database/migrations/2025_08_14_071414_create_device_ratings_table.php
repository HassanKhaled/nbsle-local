<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_ratings', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('reservation_id');
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('device_id');

        // عناصر التقييم (من 1 لـ 5)
        $table->tinyInteger('service_quality'); 
        $table->tinyInteger('device_info_clarity');
        $table->tinyInteger('search_interface');
        $table->tinyInteger('request_steps_clarity');
        $table->tinyInteger('device_condition');
        $table->tinyInteger('research_results_quality');
        $table->tinyInteger('device_availability');
        $table->tinyInteger('response_speed');
        $table->tinyInteger('technical_support');
        $table->tinyInteger('research_success');
        $table->tinyInteger('recommend_service');

        // ملاحظات عامة
        $table->text('feedback')->nullable();

        $table->timestamps();

        $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_ratings');
    }
}
