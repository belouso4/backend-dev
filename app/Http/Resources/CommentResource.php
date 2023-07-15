<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'created_at' => $this->created_at->format('d/m/Y'),
            'id' => $this->id,
            'likes_count' => $this->likes_count ?? 0,
            'parent_id' => $this->parent_id,
            'replies' => CommentResource::collection($this->replies),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => [
                    'small' => Helper::getPathIfExist('avatar/small/', $this->user->avatar),
                    'original' => Helper::getPathIfExist('avatar/original/', $this->user->avatar),
                ],
            ],
            'user_id' => $this->user_id,
            'user_like_count' => $this->user_like_count ?? 0
        ];
    }
}
