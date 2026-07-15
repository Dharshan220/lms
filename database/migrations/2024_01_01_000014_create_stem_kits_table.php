<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stem_kits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category');
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);
            $table->decimal('price', 8, 2)->default(0);
            $table->string('image')->nullable();
            $table->text('components')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('stock_quantity')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stem_kits');
    }
};
