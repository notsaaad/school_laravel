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
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('discountType')->nullable()->constrained('definitions')->onDelete('set null');
            $table->foreignId('referralSource')->nullable()->constrained('definitions')->onDelete('set null');
            $table->foreignId('specialStatus')->nullable()->constrained('definitions')->onDelete('set null');
            $table->foreignId('place_id')->nullable()->constrained()->onDelete('cascade')->onDelete('set null');
            $table->text("notes")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('discountType');
            $table->dropColumn('referralSource');
            $table->dropColumn('specialStatus');
            $table->dropColumn('place_id');
            $table->dropColumn('notes');
        });
    }
};
