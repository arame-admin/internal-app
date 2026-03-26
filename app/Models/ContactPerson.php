<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ContactPerson Model
 *
 * Represents a contact person for a client.
 *
 * @property int $id Primary key
 * @property int $client_id Foreign key to clients table
 * @property string $name Contact person name
 * @property string|null $email Contact person email
 * @property string|null $phone Contact person phone
 * @property string|null $designation Contact person designation
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 * @property-read Client $client The client this contact belongs to
 */
class ContactPerson extends Model
{
    use HasFactory;

    protected $table = 'contact_persons';

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'designation',
    ];

    protected $casts = [
        'client_id' => 'integer',
    ];

    /**
     * Get the client that owns this contact person.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}