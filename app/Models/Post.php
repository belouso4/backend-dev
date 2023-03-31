<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;

use Request;

class Post extends Model
{
    use SoftDeletes, HasFactory, Sluggable;

    public $timestamps = true;

    protected $table = 'posts';

    protected $hidden = ['pivot'];

    protected $perPage = 10;

//    protected static function booted()
//    {
//        static::addGlobalScope('trashed', function (Builder $builder) {
//            $builder->withTrashed();
//        });
//    }

protected $fillable = [
        'title',
        'desc',
        'status',
        'excerpt',
        'meta_title',
        'meta_desc',
        'meta_keywords',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

//    public function getRouteKey(): string
//    {
//        return $this->id;
//    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function likes(){
        return $this->belongsToMany( 'App\Models\User', 'users_posts_likes', 'post_id', 'user_id');
    }

    public function userLike(){
        $userID = Request::user('sanctum') != '' ? Request::user('sanctum')->id : null;

        return $this->belongsToMany( 'App\Models\User', 'users_posts_likes', 'post_id', 'user_id')
            ->where('user_id', $userID );
    }

    public function postView()
    {
        return $this->hasMany(PostView::class);
    }

    public function showPost()
    {
        if(auth()->id()==null){
            return $this->postView()
                ->where('ip', '=',  request()->ip())->exists();
        }

        return $this->postView()
            ->where(function($postViewsQuery) {
                $postViewsQuery->where('session_id', '=', request()->getSession()->getId())
                    ->orWhere('user_id', '=', auth()->id());
            })->exists();
    }

    public function comments() {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

//    public function addedBy()
//    {
//        return $this->hasOne('App\Models\User', 'id',
//            'added_by');
//    }

//
//    public function getRouteKeyName()
//    {
//        return 'slug';
//    }


}
