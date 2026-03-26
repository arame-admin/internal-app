<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->foreignId('project_task_id')->nullable()->constrained('project_tasks')->onDelete('set null')->after('project_id');
            $table->dropColumn('task');
        });
    }

    public function down(): void
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->string('task')->nullable()->after('project_id');
            $table->dropForeign(['project_task_id']);
            $table->dropColumn('project_task_id');
        });
    }
};

