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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img')->nullable();
            $table->float('price');
            $table->float('sell_price');
            $table->enum('gender', ['boy', 'girl', "both"])->nullable();
            $table->foreignId('stage_id')->nullable()->constrained()->onDelete('set null');
            $table->float('stock')->default(0);
            $table->string('sku')->nullable();
            $table->boolean('show')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
