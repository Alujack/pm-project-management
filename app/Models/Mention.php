<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mention extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'mentioning_user_id', 'mentioned_user_id', 'message', 'read'];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function mentioningUser()
    {
        return $this->belongsTo(User::class, 'mentioning_user_id');
    }

    public function mentionedUser()
    {
        return $this->belongsTo(User::class, 'mentioned_user_id');
    }
}