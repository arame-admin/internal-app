<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the contact persons for this client.
     */
    public function contactPersons(): HasMany
    {
        return $this->hasMany(ContactPerson::class);
    }
}