<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Leave Model
 *
 * Represents leave configuration for a year.
 * Handles leave allocations including sick, casual, and earned leaves.
 *
 * @property int $id Primary key
 * @property int $year Year for leave configuration
 * @property float $sick_leave Number of sick leaves
 * @property float $casual_leave Number of casual leaves
 * @property float $earned_leaves Number of earned leaves
 * @property bool $status Active status
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 */
class Leave extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'leaves';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'year',
        'sick_leave',
        'casual_leave',
        'earned_leaves',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'sick_leave' => 'decimal:2',
        'casual_leave' => 'decimal:2',
        'earned_leaves' => 'decimal:2',
        'status' => 'string',
    ];
}
