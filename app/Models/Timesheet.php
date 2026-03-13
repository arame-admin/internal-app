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
        'hours',
        'description',
        'status',
        'approved_by',
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:2',
        'approved_by' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
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
}
?>

