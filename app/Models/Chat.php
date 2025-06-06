<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Chat extends Model
{
    use HasFactory;

    protected $fillable= [
        'issue_id',
        'user_id',
        'message'
    ];

    public function issue(){
        return $this->belongsTo(Issue::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
