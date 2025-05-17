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
        Schema::create('installment', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('yearcost_id');
          $table->integer('per'); 
          $table->timestamps();
          $table->foreign('yearcost_id')->references('id')->on('yearcost')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment');
    }
};
