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
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique(); // optional product-level SKU
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00); // base price
            $table->string('brand')->nullable();
            $table->json('meta')->nullable(); // for flexible attributes
            $table->boolean('active')->default(true);
            $table->integer('position')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
