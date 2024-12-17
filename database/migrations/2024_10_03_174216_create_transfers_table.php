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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code');

            $table->enum('type', ["pending", "paid", "complete"])->default("pending");

            $table->foreignId("enteredBy")->nullable()->constrained("users")->onDelete("set null");
            $table->foreignId("paidBy")->nullable()->constrained("users")->onDelete("set null");
            $table->foreignId("confirmBy")->nullable()->constrained("users")->onDelete("set null");

            $table->dateTime("paid_at")->nullable();
            $table->dateTime("confirmed_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
