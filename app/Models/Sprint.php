<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'startDate',
        'endDate',
        'sprintGoal',
        'ownerID',
        'project_id'
    ];

    protected $casts = [
        'startDate' => 'datetime',
        'endDate' => 'datetime',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'ownerID');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    
    

    public function issues()
    {
        return $this->hasMany(Issue::class, 'sprintID');
    }
} 