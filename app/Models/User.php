<?php

namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Department;
use App\Models\Designation;
use App\Models\BusinessUnit;
use App\Models\Location;

/**
 * User Model
 *
 * Represents a user in the system with authentication capabilities.
 * Users have roles and can access different parts of the application.
 *
 * @property int $id Primary key
 * @property string $name User's full name
 * @property string $first_name User's first name
 * @property string $last_name User's last name
 * @property string $email User's email address
 * @property string $personal_email User's personal email
 * @property string $phone_country_code Phone country code
 * @property string $phone_number User's phone number
 * @property string $password Hashed password
 * @property string $about_me User's about me description
 * @property string $what_i_love_about_job What user loves about their job
 * @property string $gender User's gender
 * @property \Carbon\Carbon $dob User's date of birth
 * @property string $marital_status User's marital status
 * @property \Carbon\Carbon $marriage_date User's marriage date
 * @property string $blood_group User's blood group
 * @property bool $physically_handicapped Whether user is physically handicapped
 * @property string $nationality User's nationality
 * @property string $work_email User's work email
 * @property string $work_number User's work phone number
 * @property string $residence_number User's residence phone number
 * @property string $current_address User's current address
 * @property string $permanent_address User's permanent address
 * @property string $employee_code User's employee code
 * @property \Carbon\Carbon $date_of_joining User's joining date
 * @property string $job_title User's job title
 * @property int $role_id Foreign key to roles table
 * @property int $department_id Foreign key to departments table
 * @property int $designation_id Foreign key to designations table
 * @property int $bu_id Foreign key to business_units table
 * @property int $location_id Foreign key to locations table
 * @property bool $is_active User's active status
 * @property string|null $remember_token Remember token for authentication
 * @property \Carbon\Carbon|null $email_verified_at Email verification timestamp
 * @property \Carbon\Carbon $created_at Creation timestamp
 * @property \Carbon\Carbon $updated_at Update timestamp
 * @property-read Role $role The user's role relationship
 * @property-read Department $department The user's department relationship
 * @property-read Designation $designation The user's designation relationship
 * @property-read BusinessUnit $businessUnit The user's business unit relationship
 * @property-read Location $location The user's location relationship
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'personal_email',
        'phone_country_code',
        'phone_number',
        'password',
        'about_me',
        'what_i_love_about_job',
        'gender',
        'dob',
        'marital_status',
        'marriage_date',
        'blood_group',
        'physically_handicapped',
        'nationality',
        'work_email',
        'work_number',
        'residence_number',
        'current_address',
        'permanent_address',
        'employee_code',
        'date_of_joining',
        'job_title',
        'role_id',
        'department_id',
        'designation_id',
        'bu_id',
        'location_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the role that belongs to the user.
     *
     * @return BelongsTo<Role, User>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the department that belongs to the user.
     *
     * @return BelongsTo<Department, User>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the designation that belongs to the user.
     *
     * @return BelongsTo<Designation, User>
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Get the business unit that belongs to the user.
     *
     * @return BelongsTo<BusinessUnit, User>
     */
    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class, 'bu_id');
    }

    /**
     * Get the location that belongs to the user.
     *
     * @return BelongsTo<Location, User>
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'marriage_date' => 'date',
            'date_of_joining' => 'date',
            'physically_handicapped' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
