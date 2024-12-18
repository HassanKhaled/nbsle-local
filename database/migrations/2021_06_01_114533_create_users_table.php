<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /*** Run the migrations.*/
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
//            $table->string('name');
            $table->string('log_email')->nullable();
            $table->string('password');
            $table->string('password_hashed');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
//            $table->unsignedBigInteger('uni_id')->nullable();
            $table->foreignId('uni_id')->nullable()->constrained('universitys')->onDelete('cascade');
//            $table->unsignedBigInteger('fac_id')->nullable();
            $table->foreignId('fac_id')->nullable()->constrained('facultys')->onDelete('cascade');
//            $table->unsignedBigInteger('dept_id')->nullable();
            $table->foreignId('dept_id')->nullable()->constrained('departments')->onDelete('cascade');
//            $table->unsignedBigInteger('lab_id')->nullable();
//            $table->unsignedBigInteger('UFId')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /*** Reverse the migrations.*/
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
