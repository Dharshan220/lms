<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stem_kits', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published'])->default('draft')->after('is_available');
        });

        DB::table('stem_kits')->where('is_available', true)->update(['status' => 'published']);
    }

    public function down(): void
    {
        Schema::table('stem_kits', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};