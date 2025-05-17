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
        Schema::create('yearcost_stages', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('yearcost_id');
          $table->unsignedBigInteger('stage_id');
          $table->decimal('book', 10, 2)->default(0);
          $table->decimal('cash', 10, 2)->default(0);
          $table->decimal('installment', 10, 2)->default(0);
          $table->timestamps();
          $table->foreign('yearcost_id')->references('id')->on('yearcost')->onDelete('cascade');
          $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearcost_stages');
    }
};
