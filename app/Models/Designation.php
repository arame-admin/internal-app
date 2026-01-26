<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Designation Model
 *
 * Represents a designation in the organization.
 * Handles designation data including name, code, description, and status.
 *
 * @property int $id Primary key
 * @property string $name Designation name
 * @property string $code Unique designation code
 * @property string|null $description Designation description
 * @property string $status Designation status (active/inactive)
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 */
class Designation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'designations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'department_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the department that owns the designation.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}