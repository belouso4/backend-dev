<?php

namespace App\Http\Resources\Post;

use App\Helper\Helper;
use App\Http\Resources\Tag\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public static $wrap = '';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'created_at' => $this->created_at->format('d.m.Y'),
            'title' => $this->title,
            'excerpt' => $this->excerpt ?? '',
            'desc' => $this->desc,
            'img' => Helper::getPathIfExist('posts/', $this->img),
            'tags' => TagResource::collection($this->tags),
            'like_my' => $this->user_like_count,
            'likes_count' => $this->likes_count,
            'post_view_count' => $this->post_view_count,
            'category_name' => $this->category->name,
            'url' => Helper::getUrlWithSlugCategory($this->category, $this->slug),
            'metadata' => [
                'title' => $this->meta_title ?? '',
                'keywords' => $this->meta_keywords ?? '',
                'description' => $this->meta_desc ?? '',
            ]
        ];
    }
}
