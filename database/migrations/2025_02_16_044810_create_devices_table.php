<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_model_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_color_id')->constrained()->onDelete('cascade');
            $table->string('imei')->unique();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
