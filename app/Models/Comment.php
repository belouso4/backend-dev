<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Request;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'body'
    ];

//    protected $dates = ['created_at'];



//    public function getCreatedAtAttribute($value)
//    {
//        return $value->format('d/m/Y');
//    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with('user')
            ->withCount('userLike')
            ->withCount('likes');
    }

    public function likes(){
        return $this->belongsToMany( 'App\Models\User',
            'users_comments_likes',
            'comment_id', 'user_id');
    }

    public function userLike(){
        $userID = auth('sanctum')->user() != ''
            ? auth('sanctum')->user()->id
            : null;

        return $this->belongsToMany( 'App\Models\User',
            'users_comments_likes',
            'comment_id',
            'user_id')
            ->where('user_id', $userID );
    }

    public function isLikedByUser($user_id) {
        return (bool)$this->likes()
            ->where('user_id',$user_id)
            ->count();
    }

    public function deleteWithReplies()
    {
        if(count($this->replies) > 0) {
            // Delete children recursive
            foreach ($this->replies as $reply) {
                $reply->deleteWithReplies();
            }
        }
        $this->likes()->detach();
        $this->delete();
    }
}
