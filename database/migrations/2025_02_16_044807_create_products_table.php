<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->decimal('retail_price', 8, 2);
            $table->decimal('purchase_price', 8, 2);
            $table->text('desc')->nullable();
            $table->foreignId('product_category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
