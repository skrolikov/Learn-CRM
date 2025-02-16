<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceModelsTable extends Migration
{
    public function up()
    {
        Schema::create('device_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('device_type_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('device_models');
    }
}
