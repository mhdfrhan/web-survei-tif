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
        Schema::create('survey_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_type_id')->constrained()->onDelete('cascade');
            $table->string('field_name'); // program_studi, tahun_masuk, unit_kerja, masa_kerja
            $table->string('field_label'); // Label yang ditampilkan
            $table->enum('field_type', ['text', 'number', 'date', 'select', 'textarea', 'email']);
            $table->json('field_options')->nullable(); // Untuk select options
            $table->boolean('is_required')->default(false);
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
        Schema::dropIfExists('survey_form_fields');
    }
};
