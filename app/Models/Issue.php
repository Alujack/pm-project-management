<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'startDate',
        'endDate',
        'duration',
        'statusID',
        'sprintID',
        'projectID',
        'userID',
        'assigneeID',
        'assignerID',
        'priority',
    ];

    protected $casts = [
        'startDate' => 'datetime',
        'endDate' => 'datetime',
        'priority' => 'string',
    ];

    // Relationships
    public function status()
    {
        return $this->belongsTo(Status::class, 'statusID');
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class, 'sprintID');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'projectID');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigneeID');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assignerID');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'issueID');
    }
   
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function chats(){
        return $this->hasMany(Chat::class)->latest();
    }
}