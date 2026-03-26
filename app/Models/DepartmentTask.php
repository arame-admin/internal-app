<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DepartmentTask Model
 *
 * Represents a task available for a department.
 *
 * @property int $id Primary key
 * @property int $department_id Foreign key to departments table
 * @property string $name Task name
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 * @property-read Department $department The department this task belongs to
 */
class DepartmentTask extends Model
{
    use HasFactory;

    protected $table = 'department_tasks';

    protected $fillable = [
        'department_id',
        'name',
    ];

    protected $casts = [
        'department_id' => 'integer',
    ];

    /**
     * Get the department that owns this task.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}