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
        Schema::table('users', function (Blueprint $table) {
            // Personal Information
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('personal_email')->unique()->nullable()->after('email');
            $table->string('phone_country_code', 5)->nullable()->after('personal_email');
            $table->string('phone_number')->nullable()->after('phone_country_code');
            $table->text('about_me')->nullable()->after('phone_number');
            $table->text('what_i_love_about_job')->nullable()->after('about_me');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('what_i_love_about_job');
            $table->date('dob')->nullable()->after('gender');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable()->after('dob');
            $table->date('marriage_date')->nullable()->after('marital_status');
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable()->after('marriage_date');
            $table->boolean('physically_handicapped')->default(false)->after('blood_group');
            $table->string('nationality')->nullable()->after('physically_handicapped');

            // Work Information
            $table->string('work_email')->unique()->nullable()->after('nationality');
            $table->string('work_number')->nullable()->after('work_email');
            $table->string('residence_number')->nullable()->after('work_number');
            $table->text('current_address')->nullable()->after('residence_number');
            $table->text('permanent_address')->nullable()->after('current_address');
            $table->string('employee_code')->unique()->nullable()->after('permanent_address');
            $table->date('date_of_joining')->nullable()->after('employee_code');
            $table->string('job_title')->nullable()->after('date_of_joining');

            // Relationships
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null')->after('job_title');
            $table->foreignId('designation_id')->nullable()->constrained('designations')->onDelete('set null')->after('department_id');
            $table->foreignId('bu_id')->nullable()->constrained('business_units')->onDelete('set null')->after('designation_id');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null')->after('bu_id');

            // Status
            $table->boolean('is_active')->default(true)->after('location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['department_id']);
            $table->dropForeign(['designation_id']);
            $table->dropForeign(['bu_id']);
            $table->dropForeign(['location_id']);

            // Drop all added columns
            $table->dropColumn([
                'first_name',
                'last_name',
                'personal_email',
                'phone_country_code',
                'phone_number',
                'about_me',
                'what_i_love_about_job',
                'gender',
                'dob',
                'marital_status',
                'marriage_date',
                'blood_group',
                'physically_handicapped',
                'nationality',
                'work_email',
                'work_number',
                'residence_number',
                'current_address',
                'permanent_address',
                'employee_code',
                'date_of_joining',
                'job_title',
                'department_id',
                'designation_id',
                'bu_id',
                'location_id',
                'is_active'
            ]);
        });
    }
};