<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Invitation extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['email', 'username', 'project_id', 'token', 'accepted'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}