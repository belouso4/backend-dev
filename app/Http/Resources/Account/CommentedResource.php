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
        $comment = $this->only('id', 'body', 'parent_id', 'user.id');
        $comment['user'] = $this->user;

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
