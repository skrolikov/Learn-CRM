<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('sub_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('type_status_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('sub_statuses');
    }
}
