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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('stage_id')->nullable()->constrained('stages')->onDelete('set null');
            $table->enum('study_type', ['national', 'international']);
            $table->enum('gender', ['boy', 'girl'])->nullable();

            $table->foreignId('year_id')->nullable()->constrained('years')->onDelete('set null');
            $table->string('phone1');
            $table->string('phone2')->nullable();
            $table->foreignId('fees_id')->constrained('application_fees');

            $table->enum('status', ["new", "paid", "complate", "returned", "canceled"])->default("new");

            $table->enum('can_share', ["yes", "no"])->default("no");


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
