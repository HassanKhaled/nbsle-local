<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeptFacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dept_fac', function (Blueprint $table) {
           // $table->string('name');
//            $table->unsignedBigInteger('dept_id');
            $table->foreignId('dept_id')->constrained('departments')->onDelete('cascade')->nullable();
//            $table->unsignedBigInteger('uni_id');
            $table->foreignId('uni_id')->constrained('universitys')->onDelete('cascade')->nullable();
//            $table->unsignedBigInteger('fac_id');
            $table->foreignId('fac_id')->constrained('facultys')->onDelete('cascade')->nullable();
            $table->primary(array('dept_id', 'uni_id' ,'fac_id'));
            $table->unsignedBigInteger('coor_id')->nullable();
//            $table->foreign('coor_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('dept_fac');
    }
}
