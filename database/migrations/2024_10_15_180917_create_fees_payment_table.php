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
        Schema::create('fees_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payable_id');
            $table->string('payable_type');

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('auth_id')->constrained('users')->onDelete('cascade');
            $table->float('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees_payment');
    }
};
