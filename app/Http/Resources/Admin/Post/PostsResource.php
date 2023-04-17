<?php

namespace App\Http\Resources\Admin\Post;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'img' => Storage::url($this->img),
            'status' => $this->status,
            'url' => $this->getUrl($this->category, $this->slug),
        ];
    }

    public function getUrl($category, $slug)
    {
        $url = $this->recursiveAddSlugCategory($category);
        return '/' . implode('/', array_reverse($url)) . '/article/' . $slug;
    }

    public function recursiveAddSlugCategory( $input) {
        $even[] = $input->slug;

        if( $input->parent ) {
            $even = array_merge(
                $even,
                $this->recursiveAddSlugCategory( $input->parent)
            );;
        }

        return $even;
    }
}
