<?php

namespace App\Http\Resources\Admin\Post;

use App\Helper\Helper;
use App\Http\Resources\Admin\Tag\TagResource;
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
            'category_id' => $this->category_id,
            'desc' => $this->desc,
            'excerpt' => $this->excerpt ?? '',
            'img' => Helper::getPathIfExist('posts/', $this->img),
            'status' => $this->status,
            'tags' => TagResource::collection($this->tags),
            'meta_title' => $this->meta_title ?? '',
            'meta_desc' => $this->meta_desc ?? '',
            'meta_keywords' => $this->meta_keywords ?? '',
        ];
    }
}
