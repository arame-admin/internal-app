<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('company_holidays');
        
        Schema::create('company_holidays', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->unique();
            $table->json('mandatory_holidays')->nullable();
            $table->json('optional_holidays')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_holidays');
    }
};
