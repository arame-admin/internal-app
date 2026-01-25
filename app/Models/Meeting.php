<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'meeting_date',
        'meeting_time',
        'duration',
        'location',
        'meeting_type',
        'attendees',
        'agenda',
        'discussion_points',
        'decisions',
        'action_items',
        'next_meeting_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'meeting_time' => 'datetime',
        'next_meeting_date' => 'date',
        'attendees' => 'array',
        'agenda' => 'array',
        'discussion_points' => 'array',
        'decisions' => 'array',
        'action_items' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}