<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Slider\MainSliderResource;
use App\Models\Slider;


class OtherController extends Controller
{
    public function index()
    {
        $slider = Slider::select(['id', 'post_id', 'img', 'order'])
            ->orderBy('order')
            ->with('post:id,excerpt,title,img')
            ->orderBy('order')
            ->get();

        return MainSliderResource::collection($slider);
    }
}
