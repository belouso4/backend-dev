<?php

namespace App\Http\Resources\Other;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class PostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->diffForHumans(),
            'title' => $this->title,
            'excerpt' => $this->excerpt ?? '',
            'post_view_count' => $this->post_view_count,
            'category_name' => $this->category->name,
            'category_url' => Helper::getUrlWithSlugCategory($this->category),
            'url' => Helper::getUrlWithSlugCategory($this->category, $this->slug),
        ];
    }
}
