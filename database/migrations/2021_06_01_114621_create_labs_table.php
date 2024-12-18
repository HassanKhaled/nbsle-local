<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('Arabicname')->nullable();
            $table->unsignedBigInteger('dept_id')->nullable();
//            $table->foreign('dept_id')->references('dept_id')->on('dept_fac')->onDelete('cascade');
//            $table->unsignedBigInteger('uni_id');
            $table->foreignId('uni_id')->constrained('universitys')->onDelete('cascade')->nullable();
//            $table->unsignedBigInteger('fac_id');
            $table->foreignId('fac_id')->constrained('facultys')->onDelete('cascade')->nullable();
            $table->string('pic')->nullable();
            $table->string('ImagePath')->nullable();
            $table->string('services')->nullable();
            $table->boolean('accredited')->default(False);
            $table->dateTime('accredited_date')->nullable();
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
        Schema::dropIfExists('labs');
    }
}
