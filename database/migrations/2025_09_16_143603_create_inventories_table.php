<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->integer('quantity_change'); // + or -
            $table->integer('quantity_after'); // resulting stock
            $table->string('reason')->nullable(); // sale, manual_adjust, restock
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // who checked/changed
            $table->json('metadata')->nullable(); // optional extra info (order_id, supplier)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
