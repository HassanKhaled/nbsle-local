<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uni_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('Arabicname')->nullable();
//            $table->unsignedBigInteger('lab_id');
            $table->foreignId('lab_id')->constrained('uni_labs')->onDelete('cascade')->nullable();
//            $table->unsignedBigInteger('uni_id');
            $table->foreignId('uni_id')->constrained('universitys')->onDelete('cascade')->nullable();
            $table->string('model')->nullable();
            $table->string('pic')->nullable();
            $table->string('ImagePath')->nullable();
            $table->integer('num_units')->default(0);
            $table->text('cost')->nullable();
            $table->text('services')->nullable();
            $table->string('servicesArabic')->nullable();
            $table->string('costArabic')->nullable();
            $table->text('description')->nullable();
            $table->string('ArabicDescription')->nullable();
            $table->string('AdditionalInfo')->nullable();
            $table->string('ArabicAddInfo')->nullable();
            $table->string('manufacturer')->nullable();
            $table->date('ManufactureYear')->nullable();
            $table->smallInteger('MaintenanceContract')->nullable();
            $table->string('ManufactureCountry')->nullable();
            $table->string('ManufactureWebsite')->nullable();
            $table->date('entry_date')->default(date("Y-m-d H:i:s"));
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
        Schema::dropIfExists('uni_devices');
    }
}
