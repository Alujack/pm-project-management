<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mention extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'mentioning_user_id',
        'mentioned_user_id',
        'mentioned_email',
        'message',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who made the mention
     */
    public function mentioningUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentioning_user_id');
    }

    /**
     * Get the user who was mentioned
     */
    public function mentionedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentioned_user_id');
    }

    /**
     * Get the project this mention belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Check if this mention is for an invited user
     */
    public function isForInvitedUser(): bool
    {
        return is_null($this->mentioned_user_id) && !is_null($this->mentioned_email);
    }

    /**
     * Scope to get unread mentions
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope to get mentions for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('mentioned_user_id', $userId);
    }
}