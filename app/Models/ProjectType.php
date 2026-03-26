<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProjectType Model
 *
 * Represents a type/category of a project.
 *
 * @property int $id Primary key
 * @property int $project_id Foreign key to projects table
 * @property string $type Project type
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 * @property-read Project $project The project this type belongs to
 */
class ProjectType extends Model
{
    use HasFactory;

    protected $table = 'project_types';

    protected $fillable = [
        'project_id',
        'type',
    ];

    protected $casts = [
        'project_id' => 'integer',
    ];

    /**
     * Get the project that owns this type.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}