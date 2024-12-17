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
        Schema::create('student_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained('users')->onDelete('cascade');

            // البيانات الأساسية
            $table->enum('study_type', ['national', 'international'])->nullable(); // نوع الدراسة (ناشونال أو إنترناشونال)
            $table->date('join_date')->nullable(); // تاريخ الالتحاق
            $table->string('national_id', 14)->unique()->nullable(); // الرقم القومي
            $table->date('birth_date')->nullable(); // تاريخ الميلاد
            $table->enum('religion', ['مسلم', 'مسيحي', 'اخر'])->nullable(); // الديانة (مسلم، مسيحي، أو آخر)

            $table->foreignId("region_id")->nullable()->constrained("regions")->onDelete("set null");
            $table->foreignId('nationality_id')->nullable()->constrained('definitions')->onDelete('set null');


            // بيانات الأب
            $table->string('father_phone')->nullable(); // موبيل الوالد
            $table->string('father_whatsapp')->nullable(); // واتس الوالد
            $table->string('father_national_id', 14)->unique()->nullable(); // الرقم القومي للوالد
            $table->foreignId('father_nationality_id')->nullable()->constrained('definitions')->onDelete('set null');

            $table->foreignId('father_job_id')->nullable()->constrained('definitions')->onDelete('set null');
            $table->string('father_workplace')->nullable(); // جهة عمل الأب
            $table->foreignId('father_qualification_id')->nullable()->constrained('definitions')->onDelete('set null');
            $table->boolean('father_alive')->default(true); // هل الأب على قيد الحياة؟

            // بيانات الأم
            $table->string('mother_name')->nullable(); // اسم الوالدة
            $table->string('mother_phone')->nullable(); // موبيل الوالدة
            $table->string('mother_whatsapp')->nullable(); // واتس الوالدة
            $table->string('mother_national_id', 14)->unique()->nullable(); // الرقم القومي للوالدة
            $table->foreignId('mother_nationality_id')->nullable()->constrained('definitions')->onDelete('set null');

            $table->foreignId('mother_job_id')->nullable()->constrained('definitions')->onDelete('set null');
            $table->string('mother_workplace')->nullable(); // جهة عمل الوالدة
            $table->foreignId('mother_qualification_id')->nullable()->constrained('definitions')->onDelete('set null');
            $table->boolean('mother_alive')->default(true); // هل الوالدة على قيد الحياة؟

            // القرابة
            $table->foreignId('kinship_id')->nullable()->constrained('definitions')->onDelete('set null');
            $table->string('kinship_name')->nullable(); // اسم ولي الأمر
            $table->string('kinship_national_id', 14)->unique()->nullable(); // الرقم القومي لولي الأمر
            $table->foreignId('kinship_job_id')->nullable()->constrained('definitions')->onDelete('set null');
            $table->string('kinship_type', 100)->nullable(); // نوع الوصاية
            $table->string('kinship_reason', 255)->nullable(); // سبب الوصاية
            $table->text('kinship_notes')->nullable(); // الملاحظات
            $table->string('kinship_email')->nullable(); // إيميل ولي الأمر
            $table->string('kinship_phone')->nullable(); // تليفون ولي الأمر
            $table->string('kinship2_name')->nullable(); // تليفون ولي الأمر الثاني
            $table->string('kinship2_phone')->nullable(); // تليفون ولي الأمر الثاني
            $table->foreignId('kinship2_id')->nullable()->constrained('definitions')->onDelete('set null');

            // دمج
            $table->foreignId('disability_id')->nullable()->constrained('definitions')->onDelete('set null');
            $table->text('disability_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_details');
    }
};
