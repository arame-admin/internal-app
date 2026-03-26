<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'project_type',
                'technologies',
                'features',
                'assigned_users',
                'team_members'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('project_type')->nullable();
            $table->json('technologies')->nullable();
            $table->json('features')->nullable();
            $table->json('assigned_users')->nullable();
            $table->json('team_members')->nullable();
        });
    }
};

