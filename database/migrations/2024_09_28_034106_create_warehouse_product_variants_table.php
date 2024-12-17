<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['warehouse_id', 'product_id']);
        });

        Schema::create('warehouse_product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_product_id')->constrained('warehouse_products')->cascadeOnDelete();;
            $table->foreignId('variant_id')->constrained('variants')->cascadeOnDelete();;
            $table->integer('stock')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_products');
        Schema::dropIfExists('warehouse_product_variants');
    }
};
