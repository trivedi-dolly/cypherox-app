<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'post';

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    function comments() {
        return $this->hasMany(Comment::class);
    }
    function user()  {
        return $this->belongsTo(User::class);
    }
}
