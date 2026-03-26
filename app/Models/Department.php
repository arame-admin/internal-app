<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the designations for the department.
     */
    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

    /**
     * Get the tasks for this department.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(DepartmentTask::class);
    }
}