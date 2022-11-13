<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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

        $avatar = Storage::exists($this->user->avatar) ?  '' : 'avatar/';
        return [
            'body' => $this->body,
            'created_at' => $this->created_at,
            'id' => $this->id,
            'likes_count' => $this->likes_count,
            'parent_id' => $this->parent_id,
            'replies' => CommentResource::collection($this->replies),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => env('APP_URL'). '/storage/' . $avatar . $this->user->avatar,
            ],
            'user_id' => $this->user_id,
            'user_like_count' => $this->user_like_count
        ];
    }
}
