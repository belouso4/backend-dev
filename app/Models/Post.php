<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;
use Request;

class Post extends Model
{
    use SoftDeletes, HasFactory, Searchable;

    public $timestamps = true;

//    protected $hidden = ['pivot'];

    protected $perPage = 10;

    protected $with = ['category'];

    protected $fillable = [
        'title',
        'desc',
        'status',
        'excerpt',
        'meta_title',
        'meta_desc',
        'meta_keywords',
        'category_id'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function shouldBeSearchable()
    {
        return $this->isPublished();
    }

    public function isPublished() {
        return $this->status == 1;
    }

    public function toSearchableArray(): array
    {
        return [
            'id'   => $this->getKey(),
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'desc' => strip_tags($this->desc),
            'img' => Helper::getPathIfExist('posts/', $this->img),
            'url' => Helper::getUrlWithSlugCategory($this->category, $this->slug),
            'model' => 'post',
        ];
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function postView()
    {
        return $this->hasMany(PostView::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function likes()
    {
        return $this->belongsToMany( 'App\Models\User', 'users_posts_likes', 'post_id', 'user_id');
    }

    public function views()
    {
        return $this->belongsToMany( 'App\Models\User', 'post_views', 'post_id', 'user_id');
    }

    public function userLike()
    {
        $userID = Request::user('sanctum') != '' ? Request::user('sanctum')->id : null;

        return $this->belongsToMany( 'App\Models\User', 'users_posts_likes', 'post_id', 'user_id')
            ->where('user_id', $userID );
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
}
