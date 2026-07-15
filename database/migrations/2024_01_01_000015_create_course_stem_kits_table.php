<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_stem_kits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('stem_kit_id')->constrained('stem_kits')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['course_id', 'stem_kit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_stem_kits');
    }
};
