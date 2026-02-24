<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyHoliday extends Model
{
    protected $fillable = [
        'year',
        'mandatory_holidays',
        'optional_holidays',
    ];

    protected $casts = [
        'year' => 'integer',
        'mandatory_holidays' => 'array',
        'optional_holidays' => 'array',
    ];
}
