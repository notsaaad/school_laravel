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
        Schema::table('order_datails', function (Blueprint $table) {
            $table->boolean('picked')->default(false)->after('sell_price');
            $table->dateTime('picked_at')->nullable()->after('picked');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_datails', function (Blueprint $table) {
            $table->dropColumn('picked');
            $table->dropColumn('picked_at');
        });
    }
};
