<?php

namespace App\Models;

use App\Enums\RoleSystem;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasFactory;

    // In User.php
    protected $fillable = [
        'username',
        'email',
        'password',
        'profileURL',
        'avatar',
        'cover_photo',
        'bio',
        'phone',
        'gender',
        'systemRole',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'gender' => 'string',
        'systemRole' => 'string',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
    ];


    public function mentions()
    {
        return $this->hasMany(Mention::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->systemRole,
        ];
    }


    public function ownedProjects()
    {
        return $this->hasMany(Project::class, 'ownerID');
    }

    public function memberships()
    {
        return $this->hasMany(Member::class, 'userID');
    }

    public function ownedSprints()
    {
        return $this->hasMany(Sprint::class, 'ownerID');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'userID');
    }

    public function assignedIssues()
    {
        return $this->hasMany(Issue::class, 'assigneeID');
    }

    public function assignerIssues()
    {
        return $this->hasMany(Issue::class, 'assignerID');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'userID');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'members', 'userID', 'projectID')
            ->withPivot('role');
    }
}