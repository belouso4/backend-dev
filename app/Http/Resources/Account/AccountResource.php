<?php

namespace App\Http\Resources\Account;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'img' => Helper::getPathIfExist('posts/', $this->img),
            'url' => Helper::getUrlWithSlugCategory($this->category, $this->slug),
            'like_my' => $this->when(isset($request['exist_like']), $this->user_like_count),
            'likes_count' =>$this->when(isset($request['exist_like']), $this->likes_count),
        ];
    }
}
