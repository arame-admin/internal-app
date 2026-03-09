<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ApplyLeave Model
 *
 * Represents a leave application submitted by a user.
 *
 * @property int $id Primary key
 * @property int $user_id Foreign key to users table
 * @property string $leave_type Type of leave (sick_leave, casual_leave, earned_leave)
 * @property int $year Year of leave application
 * @property \Carbon\Carbon $start_date Start date of leave
 * @property \Carbon\Carbon $end_date End date of leave
 * @property float $total_days Total number of leave days
 * @property string $reason Reason for leave
 * @property string $status Status of leave (pending, approved, rejected)
 * @property int|null $approved_by Foreign key to users table (approver)
 * @property \Carbon\Carbon|null $approved_at Approval timestamp
 * @property string|null $rejection_reason Reason for rejection
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 * @property-read User $user The user who applied for leave
 * @property-read User|null $approver The user who approved/rejected the leave
 */
class ApplyLeave extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'apply_leaves';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'leave_type',
        'year',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'leave_type' => 'string',
        'year' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'total_days' => 'decimal:2',
        'status' => 'string',
        'approved_by' => 'integer',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user who applied for the leave.
     *
     * @return BelongsTo<User, ApplyLeave>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who approved/rejected the leave.
     *
     * @return BelongsTo<User, ApplyLeave>
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get leave balance for a user for a specific year.
     *
     * @param int $userId
     * @param int $year
     * @return array
     */
    public static function getLeaveBalance(int $userId, int $year): array
    {
        // Get leave configuration for the year
        $leaveConfig = Leave::where('year', $year)->first();
        
        if (!$leaveConfig) {
            return [
                'sick_leave' => 0,
                'casual_leave' => 0,
                'earned_leave' => 0,
                'sick_leave_used' => 0,
                'casual_leave_used' => 0,
                'earned_leave_used' => 0,
                'sick_leave_balance' => 0,
                'casual_leave_balance' => 0,
                'earned_leave_balance' => 0,
            ];
        }

        // Get used leaves for each type
        $usedLeaves = self::where('user_id', $userId)
            ->where('year', $year)
            ->where('status', 'approved')
            ->get()
            ->groupBy('leave_type');

        $sickLeaveUsed = $usedLeaves->get('sick_leave', collect())->sum('total_days');
        $casualLeaveUsed = $usedLeaves->get('casual_leave', collect())->sum('total_days');
        $earnedLeaveUsed = $usedLeaves->get('earned_leave', collect())->sum('total_days');

        return [
            'sick_leave' => $leaveConfig->sick_leave,
            'casual_leave' => $leaveConfig->casual_leave,
            'earned_leave' => $leaveConfig->earned_leaves,
            'sick_leave_used' => $sickLeaveUsed,
            'casual_leave_used' => $casualLeaveUsed,
            'earned_leave_used' => $earnedLeaveUsed,
            'sick_leave_balance' => $leaveConfig->sick_leave - $sickLeaveUsed,
            'casual_leave_balance' => $leaveConfig->casual_leave - $casualLeaveUsed,
            'earned_leave_balance' => $leaveConfig->earned_leaves - $earnedLeaveUsed,
        ];
    }
}
