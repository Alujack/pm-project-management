<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsToMany(User::class, 'members', 'projectID', 'userID')->withPivot('role');
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

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function mentions()
    {
        return $this->hasMany(Member::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
    public function sprints()
{
    return $this->hasMany(Sprint::class, 'project_id');
}


}