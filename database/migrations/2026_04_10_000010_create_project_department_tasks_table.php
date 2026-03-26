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
        Schema::create('project_department_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_department_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('project_department_id')->references('id')->on('project_departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_department_tasks');
    }
};