<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceColorsTable extends Migration
{
    public function up()
    {
        Schema::create('device_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('device_colors');
    }
}
