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
        Schema::create('survey_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_type_id')->constrained()->onDelete('cascade');
            $table->string('section_title'); // Judul section yang akan menjadi link
            $table->text('section_description')->nullable();
            $table->string('slug'); // URL slug untuk section
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index untuk performance
            $table->index(['survey_type_id', 'is_active']);
            $table->unique(['survey_type_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_sections');
    }
};
