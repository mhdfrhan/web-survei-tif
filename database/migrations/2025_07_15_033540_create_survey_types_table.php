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
        Schema::create('survey_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // VMTS Fakultas, VMTS Prodi, Mahasiswa, Dosen, Tendik
            $table->string('title'); // Judul survey
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_types');
    }
};
