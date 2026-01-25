<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Role Model
 *
 * Represents user roles in the system.
 * Roles define user permissions and access levels.
 *
 * @property int $id Primary key
 * @property string $name Role name
 * @property string|null $description Role description
 * @property bool $status Role status (active/inactive)
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users Users with this role
 */
class Role extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the users for the role.
     *
     * @return HasMany<User, Role>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}