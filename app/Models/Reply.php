<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['topic_id', 'content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
