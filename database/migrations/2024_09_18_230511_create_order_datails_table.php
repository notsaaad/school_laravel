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
        Schema::create('order_datails', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained("orders")->cascadeOnDelete();
            $table->foreignId("product_id")->nullable()->constrained("products")->onDelete("set null");
            $table->foreignId("variant_id")->nullable()->constrained("variants")->onDelete("set null");

            $table->string('discription');


            $table->integer('qnt')->default(1);
            $table->float('price')->default(0);
            $table->float('sell_price')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_datails');
    }
};
