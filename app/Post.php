<?php

namespace App;

use App\Like;
use App\User;
use App\Comment;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user(){

        return $this->belongsTo(User::class);
    }

    public function comments(){

        return $this->hasMany(Comment::class);
    }

    public function likes(){

        return $this->hasMany(Like::class);
    }

}
