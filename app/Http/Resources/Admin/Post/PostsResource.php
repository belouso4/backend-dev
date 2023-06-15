<?php

namespace App\Http\Resources\Admin\Post;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class PostsResource extends JsonResource
{
    public static $wrap = '';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'created_at' => $this->created_at->format('d.m.Y'),
            'title' => $this->title,
            'img' => Helper::getPathIfExist('posts/', $this->img),
            'status' => $this->status,
            'url' => Helper::getUrlWithSlugCategory($this->category, $this->slug),
        ];
    }


}
