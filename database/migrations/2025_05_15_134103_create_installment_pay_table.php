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
        Schema::create('installment_pay', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('yearcost_stage_id');
            $table->decimal('percentage', 5, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->date('before')->nullable(); // تاريخ الاستحقاق
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->timestamps();
            $table->foreign('yearcost_stage_id')->references('id')->on('yearcost_stages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_pay');
    }
};
