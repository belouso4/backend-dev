<?php

namespace App\Http\Resources\Other;

use Illuminate\Http\Resources\Json\JsonResource;

class PostsRaitingResource extends JsonResource
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
            'post_view_count' => $this->post_view_count,
            'likes_count' => $this->likes_count,
        ];
    }
}
