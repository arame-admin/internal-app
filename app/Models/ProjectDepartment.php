<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ProjectDepartment Model
 *
 * Represents a department/category specific to projects.
 * This is separate from employee departments stored in the Department model.
 *
 * @property int $id Primary key
 * @property string $name Department name
 * @property string $code Unique department code
 * @property string|null $description Department description
 * @property string $status Department status (active/inactive)
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 */
class ProjectDepartment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_departments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
        'available_tasks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
        'available_tasks' => 'array',
    ];

    /**
     * Get the projects for this project department.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}