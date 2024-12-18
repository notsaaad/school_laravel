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
        Schema::create('application_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('test_subjects')->onDelete('cascade');

            $table->enum('status', ['Not Tested', 'Accepted', "Rejected", "Re-Assessment"])->default('Not Tested');

            $table->string("retake_data")->nullable();

            $table->unique(['application_id', 'subject_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_subjects');
    }
};
