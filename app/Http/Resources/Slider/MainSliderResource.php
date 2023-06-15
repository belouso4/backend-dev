<?php

namespace App\Http\Resources\Slider;

use App\Http\Resources\Tag\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MainSliderResource extends JsonResource
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
            'order' => $this->order,
            'post_id' => $this->post_id,
            'title' => $this->post->title,
            'excerpt' => $this->post->excerpt,
            'img' => $this->img
                ? Storage::url($this->img)
                : Storage::url($this->post->img),
//            'tags' => TagResource::collection($this->tags),
        ];
    }
}
