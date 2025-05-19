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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->foreignId('user_id')->nullable()->constrained("users")->onDelete("set null");
            $table->foreignId('package_id')->nullable()->constrained("packages")->onDelete("set null");
            $table->float("price")->default(0);
            $table->enum("status", ["pending", "paid", "picked", "partially_picked", "canceled", "return_requested", "returned" , "to be confirmed"])->default("pending");
            $table->dateTime("picked_at")->nullable();

            $table->float("service_expenses")->nullable();
            $table->longText("reason")->nullable();

            $table->json("logs")->nullable()->default("[]");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
