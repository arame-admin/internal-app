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
        Schema::table('apply_leaves', function (Blueprint $table) {
            $table->enum('duration_type', ['full_day', 'half_day'])->default('full_day')->after('end_date');
            $table->enum('half_period', ['first_half', 'second_half'])->nullable()->after('duration_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apply_leaves', function (Blueprint $table) {
            $table->dropColumn(['duration_type', 'half_period']);
        });
    }
};

