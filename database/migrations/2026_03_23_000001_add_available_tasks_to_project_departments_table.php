<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_departments', function (Blueprint $table) {
            $table->json('available_tasks')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('project_departments', function (Blueprint $table) {
            $table->dropColumn('available_tasks');
        });
    }
};