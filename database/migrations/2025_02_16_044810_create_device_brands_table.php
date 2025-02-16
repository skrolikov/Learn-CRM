<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceBrandsTable extends Migration
{
    public function up()
    {
        Schema::create('device_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('device_model_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('device_brands');
    }
}
