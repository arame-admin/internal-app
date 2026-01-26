<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Location Model
 *
 * Represents a location in the organization.
 * Handles location data including name, code, address details, and status.
 *
 * @property int $id Primary key
 * @property string $name Location name
 * @property string $code Unique location code
 * @property string|null $address Location address
 * @property string|null $city City
 * @property string|null $state State/Province
 * @property string|null $country Country
 * @property string|null $postal_code Postal/ZIP code
 * @property string|null $description Location description
 * @property string $status Location status (active/inactive)
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 */
class Location extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Add any casts if needed
    ];
}