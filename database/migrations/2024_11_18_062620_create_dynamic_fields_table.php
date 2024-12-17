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
        Schema::create('dynamic_fields', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // اسم الحقل
            $table->string('type'); // نوع الحقل (text, number, checkbox, etc.)
            $table->text('options')->nullable(); // خيارات الحقل (للحقول مثل select أو radio)
            $table->boolean('is_required')->default(false); // هل الحقل مطلوب
            $table->softDeletes();
            $table->integer("order")->default(0);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_fields');
    }
};
