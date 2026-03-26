<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProjectTeamMember Model
 *
 * Represents a team member assigned to a project.
 *
 * @property int $id Primary key
 * @property int $project_id Foreign key to projects table
 * @property int $user_id Foreign key to users table
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 * @property-read Project $project The project this team member belongs to
 * @property-read User $user The user who is a team member
 */
class ProjectTeamMember extends Model
{
    use HasFactory;

    protected $table = 'project_team_members';

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
    ];

    protected $casts = [
        'project_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Get the project that owns this team member.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who is a team member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}