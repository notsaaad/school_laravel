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
        Schema::create('bus_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained("users")->onDelete("cascade");
            $table->foreignId("bus_id")->constrained("buses")->onDelete("cascade");

            $table->enum("status", ["pending", "not answer", "cancelled", "confirmed", "paid"])->default("pending");

            $table->string('title');
            $table->string('address');
            $table->float('price');
            $table->integer('go_chairs_count')->default(0);
            $table->integer('return_chairs_count')->default(0);
            $table->foreignId('region_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('place_id')->nullable()->constrained()->onDelete('set null');




            $table->date('date');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_orders');
    }
};
