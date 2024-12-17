<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uni_labs', function (Blueprint $table) {
            $table->id();
//            $table->unsignedBigInteger('uni_id');
            $table->foreignId('uni_id')->constrained('universitys')->onDelete('cascade')->nullable();
            $table->string('name');
            $table->string('Arabicname')->nullable();
            $table->string('location');
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
        Schema::dropIfExists('uni_labs');
    }
}
