<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Resources\Admin\Other\SliderResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class OtherController extends AdminController
{
    public function index()
    {
        $response = json_decode(Redis::get('main-slide')) ?? [];
//        $sliders = Post::select(['id', 'title', 'img'])
//            ->whereIn('id', json_decode($response))
//            ->get();

//        return SliderResource::collection(json_decode($response));
        return $response;
    }

    public function updateSliders(Request $request)
    {
        $data = $request['data'];
        $order = 1;

        foreach($data as &$v) {
            $v['order'] = $order;
            $order++;
        }

        Redis::set('main-slide', json_encode($data));

//        $sliders = Post::select(['id', 'title', 'img'])
//            ->whereIn('id', json_decode($response))
//            ->get();

        return response()->json(collect($data)->sortBy('order'));
    }
}
