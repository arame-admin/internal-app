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
        Schema::create('apply_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('leave_type', ['sick_leave', 'casual_leave', 'earned_leave']);
            $table->year('year');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_days', 5, 2);
            $table->enum('duration_type', ['full_day', 'half_day'])->default('full_day');
            $table->enum('half_period', ['first_half', 'second_half'])->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apply_leaves');
    }
};
