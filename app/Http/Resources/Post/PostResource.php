<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Tag\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'excerpt' => $this->excerpt,
            'desc' => $this->desc,
            'img' => Storage::url($this->img),
            'tags' => TagResource::collection($this->tags),
            'like_my' => $this->user_like_count,
            'likes_count' => $this->likes_count,
            'metadata' => [
                'title' => $this->meta_title ?? 0,
                'keywords' => $this->meta_keywords ?? 0,
                'description' => $this->meta_desc ?? 0,
            ]
        ];
    }
}
