<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'accessibility',
        'ownerID',
        'teamID',
    ];

    protected $casts = [
        'accessibility' => 'string',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'ownerID');
    }

    public function members()
    {
        return $this->hasMany(Member::class, 'projectID');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'projectID');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'members', 'projectID', 'userID')
                    ->withPivot('role');
    }
} 