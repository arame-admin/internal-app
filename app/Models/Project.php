<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client_id',
        'department_id',
        'project_department_id',
        'description',
        'status',
        'start_date',
        'end_date',
        'budget',
        'design_required',
        'mobile_app_required',
        'web_app_required',
        'deployment_required',
        'testing_required',
        'maintenance_required',
        'priority',
        'progress_percentage',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'design_required' => 'boolean',
        'mobile_app_required' => 'boolean',
        'web_app_required' => 'boolean',
        'deployment_required' => 'boolean',
        'testing_required' => 'boolean',
        'maintenance_required' => 'boolean',
    ];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function projectDepartment()
    {
        return $this->belongsTo(ProjectDepartment::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the technologies for this project.
     */
    public function technologies(): HasMany
    {
        return $this->hasMany(ProjectTechnology::class);
    }

    /**
     * Get the features for this project.
     */
    public function features(): HasMany
    {
        return $this->hasMany(ProjectFeature::class);
    }

    /**
     * Get the tasks for this project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }

    /**
     * Get the team members for this project.
     */
    public function teamMembers(): HasMany
    {
        return $this->hasMany(ProjectTeamMember::class);
    }

    /**
     * Get the types for this project.
     */
    public function types(): HasMany
    {
        return $this->hasMany(ProjectType::class);
    }
}