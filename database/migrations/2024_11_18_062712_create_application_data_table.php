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
        Schema::create('application_data', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade'); // التقديم المرتبط
            $table->foreignId('field_id')->constrained('dynamic_fields')->onDelete('cascade'); // الحقل المرتبط
            $table->text('value')->nullable(); // القيمة المُدخلة


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_data');
    }
};
