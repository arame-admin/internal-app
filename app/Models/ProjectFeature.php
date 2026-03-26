<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProjectFeature Model
 *
 * Represents a feature of a project.
 *
 * @property int $id Primary key
 * @property int $project_id Foreign key to projects table
 * @property string $name Feature name
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 * @property-read Project $project The project this feature belongs to
 */
class ProjectFeature extends Model
{
    use HasFactory;

    protected $table = 'project_features';

    protected $fillable = [
        'project_id',
        'name',
    ];

    protected $casts = [
        'project_id' => 'integer',
    ];

    /**
     * Get the project that owns this feature.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}