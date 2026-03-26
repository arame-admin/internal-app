<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyHoliday extends Model
{
    protected $fillable = [
        'year',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    /**
     * Get the mandatory holidays for this company holiday.
     */
    public function mandatoryHolidays(): HasMany
    {
        return $this->hasMany(MandatoryHoliday::class);
    }

    /**
     * Get the optional holidays for this company holiday.
     */
    public function optionalHolidays(): HasMany
    {
        return $this->hasMany(OptionalHoliday::class);
    }
}
