<?php

namespace App\Models;

use App\Enums\RoleSystem;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
        'profileURL',
        'gender',
        'systemRole',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'gender' => 'string',
        'systemRole' => 'string',
    ];


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

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'userID');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'members', 'userID', 'projectID')
            ->withPivot('role');
    }
}