<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    function posts()  {
        return $this->belongsTo(Post::class);
    }
}
