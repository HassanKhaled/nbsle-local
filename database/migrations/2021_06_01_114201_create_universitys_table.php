<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversitysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('universitys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
//            $table->string('pic')->nullable();
            $table->string('Arabicname')->nullable();
            $table->string('type');
            $table->string('website')->nullable();
            $table->string('ImagePath')->nullable();
            $table->unsignedBigInteger('coordinator_id')->nullable(); //default admin
//            $table->foreign('coordinator_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('universitys');
    }
}
