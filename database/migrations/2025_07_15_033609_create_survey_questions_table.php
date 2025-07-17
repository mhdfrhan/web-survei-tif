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
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('survey_section_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->enum('question_type', ['rating', 'text', 'multiple_choice', 'checkbox']);
            $table->json('options')->nullable(); // Untuk multiple choice atau rating scale
            $table->integer('min_value')->nullable(); // Untuk rating (1)
            $table->integer('max_value')->nullable(); // Untuk rating (4)
            $table->json('rating_labels')->nullable(); // Label untuk rating (1=kurang, 4=sangat baik)
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};
