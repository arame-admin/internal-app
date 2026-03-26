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
        Schema::create('optional_holidays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_holiday_id');
            $table->date('date');
            $table->string('name');
            $table->string('day')->nullable();
            $table->timestamps();

            $table->foreign('company_holiday_id')->references('id')->on('company_holidays')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('optional_holidays');
    }
};