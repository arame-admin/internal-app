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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->json('project_type')->nullable();
            $table->enum('status', ['planning', 'in_progress', 'on_hold', 'testing', 'completed', 'cancelled'])->default('planning');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->json('technologies')->nullable();
            $table->json('features')->nullable();
            $table->boolean('design_required')->default(false);
            $table->boolean('mobile_app_required')->default(false);
            $table->boolean('web_app_required')->default(false);
            $table->boolean('deployment_required')->default(false);
            $table->boolean('testing_required')->default(false);
            $table->boolean('maintenance_required')->default(false);
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->json('assigned_users')->nullable();
            $table->json('team_members')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
