<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = [
        'name', 
        'path', 
        'mime_type', 
        'size',
        'projectId',
        'user_id'
    ];
    
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}