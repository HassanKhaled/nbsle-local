<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacUniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fac_uni', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('name');
            $table->string('Arabicname')->nullable();
//            $table->string('pic')->nullable();
            $table->string('website')->nullable();
            $table->unsignedBigInteger('coor_id');
//            $table->foreign('coor_id')->references('id')->on('users');
            $table->string('ImagePath')->nullable();
//            $table->unsignedBigInteger('uni_id');
            $table->foreignId('uni_id')->constrained('universitys')->onDelete('cascade')->nullable();
//            $table->unsignedBigInteger('fac_id');
            $table->foreignId('fac_id')->constrained('facultys')->onDelete('cascade')->nullable();
//            $table->unique(['uni_id','fac_id']);

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
        Schema::dropIfExists('fac_uni');
    }
}
