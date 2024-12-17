<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateToDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('state')->default('available');
        });
        Schema::table('uni_devices', function (Blueprint $table) {
            $table->string('state')->default('available');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('state');
        });
        Schema::table('uni_devices', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
}
