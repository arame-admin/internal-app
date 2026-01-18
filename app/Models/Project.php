<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client_id',
        'description',
        'project_type',
        'status',
        'start_date',
        'end_date',
        'budget',
        'technologies',
        'features',
        'design_required',
        'mobile_app_required',
        'web_app_required',
        'deployment_required',
        'testing_required',
        'maintenance_required',
        'priority',
        'assigned_users',
        'team_members',
        'progress_percentage',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'project_type' => 'array',
        'technologies' => 'array',
        'features' => 'array',
        'assigned_users' => 'array',
        'team_members' => 'array',
        'design_required' => 'boolean',
        'mobile_app_required' => 'boolean',
        'web_app_required' => 'boolean',
        'deployment_required' => 'boolean',
        'testing_required' => 'boolean',
        'maintenance_required' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}