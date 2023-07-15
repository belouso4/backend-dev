<?php

namespace App\Http\Resources\Account;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $comment = $this->only('id', 'body', 'parent_id');
        $comment['user'] = [
            'name' => $this->user->name,
            'avatar' => [
                'small' => Helper::getPathIfExist('avatar/small/', $this->user->avatar),
                'original' => Helper::getPathIfExist('avatar/original/', $this->user->avatar),
            ],
        ];

        return [
            'id' => $this->post->id,
            'slug' => $this->post->slug,
            'title' => $this->post->title,
            'excerpt' => $this->post->excerpt,
            'img' => Helper::getPathIfExist('posts/', $this->post->img),
            'url' => Helper::getUrlWithSlugCategory($this->post->category, $this->post->slug),
            'comment' => $comment,
        ];
    }
}
