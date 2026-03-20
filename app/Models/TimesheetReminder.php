<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TimesheetReminder Model
 *
 * Represents a reminder for a user who missed applying a timesheet for a specific date.
 *
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $missed_date
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read User $user
 */
class TimesheetReminder extends Model
{
    use HasFactory;

    protected $table = 'timesheet_reminders';

    protected $fillable = [
        'user_id',
        'missed_date',
        'status',
    ];

    protected $casts = [
        'missed_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_DISMISSED = 'dismissed';

    /**
     * Get the user that owns the reminder.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the reminder is still active (within 48 hours).
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE 
            && $this->created_at->diffInHours(now()) < 48;
    }

    /**
     * Check if the reminder has expired (more than 48 hours old).
     */
    public function isExpired(): bool
    {
        return $this->created_at->diffInHours(now()) >= 48;
    }

    /**
     * Scope for active reminders.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for non-expired reminders (within 48 hours).
     */
    public function scopeNotExpired($query)
    {
        return $query->where('created_at', '>', now()->subHours(48));
    }

    /**
     * Get active reminders for a specific user.
     */
    public static function getActiveRemindersForUser(int $userId)
    {
        return self::where('user_id', $userId)
            ->where('status', self::STATUS_ACTIVE)
            ->where('created_at', '>', now()->subHours(48))
            ->orderBy('missed_date', 'desc')
            ->get();
    }
}
