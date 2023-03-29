<?php

namespace App\Http\Resources\Admin\Post;

use App\Http\Resources\Admin\Tag\TagResource;
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
            'desc' => $this->desc,
            'img' => Storage::url($this->img),
            'status' => $this->status,
            'tags' => TagResource::collection($this->tags),
        ];
    }
}
