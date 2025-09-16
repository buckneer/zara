<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersAndItemsTables extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // e.g. ORD-20250916-0001
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('shipping_total', 10, 2)->default(0.00);
            $table->decimal('tax_total', 10, 2)->default(0.00);
            $table->decimal('discount_total', 10, 2)->default(0.00);
            $table->decimal('grand_total', 10, 2)->default(0.00);
            $table->string('status')->default('pending'); // pending, processing, shipped, completed, cancelled, refunded
            $table->string('shipping_method')->nullable();
            $table->string('shipping_tracking')->nullable();
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, refunded
            $table->json('meta')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('sku')->nullable();
            $table->string('title'); // store title snapshot
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2); // stored price at purchase time
            $table->decimal('total_price', 10, 2); // unit_price * quantity minus per-line discounts
            $table->json('meta')->nullable(); // snapshot of attributes (size, color)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
}
