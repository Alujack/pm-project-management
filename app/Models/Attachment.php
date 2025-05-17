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
        'issue_id',
        'user_id'
    ];
    
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}