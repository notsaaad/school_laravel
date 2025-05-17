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
        Schema::create('student_pay', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('yearcost_stage_id');
          $table->unsignedBigInteger('installment_id')->nullable(); // nullable لو الدفع كاش
          $table->unsignedBigInteger('student_id');
          $table->enum('type', ['cash', 'installment']);
          $table->string('method'); // Visa / Manual / Transfer
          $table->decimal('amount', 10, 2);
          $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
          $table->timestamps();
          $table->foreign('yearcost_stage_id')->references('id')->on('yearcost_stages')->onDelete('cascade');
          $table->foreign('installment_id')->references('id')->on('installment')->onDelete('set null');
          $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_pay');
    }
};
