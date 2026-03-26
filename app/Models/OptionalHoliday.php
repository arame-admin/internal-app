<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * OptionalHoliday Model
 *
 * Represents an optional holiday for a company holiday configuration.
 *
 * @property int $id Primary key
 * @property int $company_holiday_id Foreign key to company_holidays table
 * @property \Carbon\Carbon $date Holiday date
 * @property string $name Holiday name
 * @property string|null $day Day of the week
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 * @property-read CompanyHoliday $companyHoliday The company holiday configuration
 */
class OptionalHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_holiday_id',
        'date',
        'name',
        'day',
    ];

    protected $casts = [
        'company_holiday_id' => 'integer',
        'date' => 'date',
    ];

    /**
     * Get the company holiday that owns this optional holiday.
     */
    public function companyHoliday(): BelongsTo
    {
        return $this->belongsTo(CompanyHoliday::class);
    }
}