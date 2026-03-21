<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Timesheet Model
 *
 * Represents daily timesheet entry by user.
 *
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $date
 * @property string|null $start_time
 * @property string|null $end_time
 * @property float $break_duration
 * @property float $hours
 * @property string|null $description
 * @property string $status
 * @property int|null $approved_by
 * @property-read User $user
 * @property-read User|null $approver
 */
class Timesheet extends Model
{
    use HasFactory;

    protected $table = 'timesheets';

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'break_duration',
        'clock_in',
        'clock_out',
        'is_timer_active',
        'hours',
        'description',
        'project_id',
        'task',
        'status',
        'approved_by',
        'rejection_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'break_duration' => 'decimal:2',
        'clock_in' => 'datetime:H:i',
        'clock_out' => 'datetime:H:i',
        'hours' => 'decimal:2',
        'approved_by' => 'integer',
        'is_timer_active' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'project_id', 'id')->where('projects.department_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Calculate hours from start_time, end_time, and break_duration.
     */
    public function calculateHours(): float
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        $start = \Carbon\Carbon::createFromFormat('H:i:s', $this->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i:s', $this->end_time);

        if ($end < $start) {
            $end->addDay();
        }

        $totalMinutes = $start->diffInMinutes($end);
        $breakMinutes = ($this->break_duration ?? 0) * 60;
        $workingMinutes = $totalMinutes - $breakMinutes;

        return round($workingMinutes / 60, 2);
    }

    /**
     * Get monthly total hours for user.
     */
    public static function monthlyTotal(int $userId, int $year, int $month): float
    {
        return self::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', 'approved')
            ->sum('hours');
    }

    /**
     * Get weekly total hours for user.
     */
    public static function weeklyTotal(int $userId): float
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        return self::where('user_id', $userId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->where('status', 'approved')
            ->sum('hours');
    }

    /**
     * Get pending timesheets for approval (subordinates).
     */
    public static function pendingForUser(User $manager)
    {
        $subordinateIds = $manager->subordinates()->pluck('id');

        return self::with('user.department')
            ->whereIn('user_id', $subordinateIds)
            ->where('status', 'pending')
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Scope for current month.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereYear('date', now()->year)
            ->whereMonth('date', now()->month);
    }

    /**
     * Check if daily minimum hours requirement is met.
     */
    public function meetsDailyMinimum(): bool
    {
        return $this->hours >= 6.5;
    }

    /**
     * Check if weekly minimum hours requirement is met.
     */
    public static function meetsWeeklyMinimum(int $userId): bool
    {
        return self::weeklyTotal($userId) >= 40;
    }

    /**
     * Get current timer duration in minutes.
     */
    public function getTimerDuration(): int
    {
        if (!$this->is_timer_active || !$this->clock_in) {
            return 0;
        }

        $clockIn = \Carbon\Carbon::createFromFormat('H:i:s', $this->clock_in);
        return \Carbon\Carbon::now()->diffInMinutes($clockIn);
    }

    /**
     * Check if user has an active timer for today.
     */
    public static function getActiveTimer(int $userId)
    {
        return self::where('user_id', $userId)
            ->where('date', now()->toDateString())
            ->where('is_timer_active', true)
            ->first();
    }
}
?>

