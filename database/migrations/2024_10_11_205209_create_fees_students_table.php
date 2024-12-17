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
        Schema::create('fees_students', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fee_id')->constrained("fees")->onDelete("cascade");
            $table->foreignId('user_id')->constrained("users")->onDelete("cascade");
            $table->unique(['fee_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees_students');
    }
};
