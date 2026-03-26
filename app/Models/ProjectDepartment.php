<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectDepartment extends Model
{
    use HasFactory;

    protected $table = 'project_departments';

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the projects for this project department.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the tasks for this project department.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectDepartmentTask::class);
    }
}