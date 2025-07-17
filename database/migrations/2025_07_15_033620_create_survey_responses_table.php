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
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('survey_section_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('respondent_id')->nullable(); // ID responden jika ada
            $table->json('form_data'); // Data form utama (program_studi, tahun_masuk, dll)
            $table->string('respondent_category')->nullable();
            $table->json('question_answers'); // Jawaban untuk setiap pertanyaan
            $table->timestamp('submitted_at');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
