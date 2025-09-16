<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->nullable()->unique(); // variant SKU
            $table->string('name')->nullable(); // "Red / M" or custom
            $table->string('size')->nullable(); // or use JSON attrs
            $table->string('color')->nullable();
            $table->decimal('price', 10, 2)->nullable(); // override price if not null
            $table->integer('stock')->default(0); // current stock
            $table->boolean('backorder')->default(false);
            $table->json('attributes')->nullable(); // flexible attributes (material, fit, etc)
            $table->softDeletes();
            $table->timestamps();

            $table->index(['product_id', 'sku']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
