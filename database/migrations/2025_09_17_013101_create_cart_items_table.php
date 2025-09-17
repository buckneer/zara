<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->integer('qty')->unsigned()->default(1);
            $table->decimal('unit_price', 12, 2)->nullable(); // snapshot of price
            $table->json('meta')->nullable(); // e.g. attributes snapshot
            $table->timestamps();

            $table->unique(['cart_id','product_id','variant_id']); // one row per product+variant in a cart
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};
