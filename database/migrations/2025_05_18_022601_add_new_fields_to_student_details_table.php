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
        Schema::table('student_details', function (Blueprint $table) {
          $table->enum('division', ['أدبي', 'علمي', 'علمي علوم', 'علمي رياضة', 'ليس تابع للنظام الثانوي'])->nullable()->after('study_type');
          $table->boolean('is_international')->default(false)->after('division');
          $table->date('residence_expiry_date')->nullable()->after('is_international');
          $table->string('student_image')->nullable()->after('residence_expiry_date');
          $table->string('name_en')->nullable()->after('student_image');
          $table->enum('enrollment_status', ['منقول', 'مستجد', 'باقي عام', 'باقي عامين', 'منقطع', 'حالة وفاة'])->nullable()->after('name_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_details', function (Blueprint $table) {
        $table->dropColumn([
            'division',
            'is_international',
            'residence_expiry_date',
            'student_image',
            'name_en',
            'enrollment_status',
        ]);
        });
    }
};
