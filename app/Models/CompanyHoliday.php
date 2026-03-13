<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyHoliday extends Model
{
    protected $fillable = [
        'year',
        'mandatory_holidays',
        'optional_holidays',
        'status',
    ];


    protected $casts = [
        'year' => 'integer',
        'mandatory_holidays' => 'array',
        'optional_holidays' => 'array',
        'status' => 'string',
    ];


    /**
     * Get all unique holiday dates for this year as YYYY-MM-DD array
     */
    public function getAllHolidayDates()
    {
        $dates = [];
        
        // Add mandatory holidays
        if ($this->mandatory_holidays) {
            foreach ($this->mandatory_holidays as $holiday) {
                if (!empty($holiday['date'])) {
                    $dates[] = $holiday['date'];
                }
            }
        }
        
        // Add optional holidays
        if ($this->optional_holidays) {
            foreach ($this->optional_holidays as $holiday) {
                if (!empty($holiday['date'])) {
                    $dates[] = $holiday['date'];
                }
            }
        }
        
        return array_unique($dates);
    }
}
