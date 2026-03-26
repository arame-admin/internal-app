<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProjectDepartmentTask Model
 *
 * Represents a task available for a project department.
 *
 * @property int $id Primary key
 * @property int $project_department_id Foreign key to project_departments table
 * @property string $name Task name
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 * @property-read ProjectDepartment $projectDepartment The project department this task belongs to
 */
class ProjectDepartmentTask extends Model
{
    use HasFactory;

    protected $table = 'project_department_tasks';

    protected $fillable = [
        'project_department_id',
        'name',
    ];

    protected $casts = [
        'project_department_id' => 'integer',
    ];

    /**
     * Get the project department that owns this task.
     */
    public function projectDepartment(): BelongsTo
    {
        return $this->belongsTo(ProjectDepartment::class);
    }
}