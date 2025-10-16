<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'comment'];

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function currentUserLike()
{
    return $this->hasOne(CommentLike::class, 'comment_id')
                ->where('user_id', Auth::id());
}

// Add this to cache the like check
protected $isLikedCache = null;

// Add this accessor to check if the comment is liked
public function getIsLikedAttribute()
{
    if ($this->isLikedCache === null) {
        $this->isLikedCache = $this->relationLoaded('currentUserLike')
            ? $this->currentUserLike !== null
            : $this->likes()->where('user_id', Auth::id())->exists();
    }
    return $this->isLikedCache;
}

// Keep isLikedByUser() for your view
public function isLikedByUser()
{
    return $this->is_liked; // Uses the new accessor
}

    // public function isLikedByUser()
    // {
    //     return $this->likes()->where('user_id', Auth::id())->exists();
    // }

    public function likeCount()
    {
        return $this->likes()->count();
    }

    public function canEditOrDelete()
    {
        return Auth::id() === $this->user_id || Auth::id() === $this->post->user_id;
    }

    
}
