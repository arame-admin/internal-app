<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProjectTechnology Model
 *
 * Represents a technology used in a project.
 *
 * @property int $id Primary key
 * @property int $project_id Foreign key to projects table
 * @property string $name Technology name
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 * @property-read Project $project The project this technology belongs to
 */
class ProjectTechnology extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
    ];

    protected $casts = [
        'project_id' => 'integer',
    ];

    /**
     * Get the project that owns this technology.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}