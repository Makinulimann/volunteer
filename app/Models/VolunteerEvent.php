<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerEvent extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'start_date',
        'end_date',
        'status',
        'banner_image',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function applications()
    {
        return $this->hasMany(VolunteerApplication::class, 'event_id');
    }

    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'volunteer_applications', 'event_id', 'user_id')
            ->wherePivot('status', 'approved')
            ->withTimestamps();
    }
}
