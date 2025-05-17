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
        Schema::create('yearcost', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('year_id');
          $table->unsignedBigInteger('system_id');
          $table->integer('installment_count')->default(0);
          $table->timestamps();
          $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');
          $table->foreign('system_id')->references('id')->on('system')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearcost');
    }
};
