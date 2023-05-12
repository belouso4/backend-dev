<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Resources\Admin\Other\SliderResource;
use App\Http\Resources\Slider\MainSliderResource;
use App\Models\Post;
use App\Models\Slider;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class OtherController extends AdminController
{
    use ImageUploadTrait;
    public function index(Request $request)
    {
        $check = $request->query('limit') !== "all";

        $query = Slider::query()->when($check, function ($q, $slider) {
            return $q->take(5);
        })
            ->with('post:id,excerpt,title,img')
            ->orderBy('order');

        $slider = $query->get();

//        $slider = Slider::select(['id', 'post_id', 'img', 'order'])
//            ->with('post:id,excerpt,title,img')
//            ->orderBy('order')
//            ->take(5)
//            ->get();

        return response()->json([
            'data' => MainSliderResource::collection($slider),
            'count' => Slider::count()
        ]);

//        $sliders = json_decode(Redis::get('main-slider')) ?? [];
//        usort($sliders, function ($a, $b) {
//            return $a->order - $b->order;
//        });
//        return MainSliderResource::collection($sliders);
    }

    public function updateSliders(Request $request)
    {
        $data = $request['data'];
        $order = 1;

        foreach($data as $v) {
            Slider::find($v)->update([
                'order' => $order
            ]);

            $order++;
        }

//        $slider = Slider::select(['id', 'post_id', 'img', 'order'])
//            ->orderBy('order')
//            ->with('post:id,excerpt,title,img')
//            ->paginate(5);

//        foreach($data as &$v) {
//            $v['order'] = $order;
//            $img = explode('/', $v['img']);
//            $v['img'] = $img[array_key_last($img)];
//            $order++;
//        }
//
//        Redis::set('main-slider', json_encode($data));
//
//        $slider = json_decode(Redis::get('main-slider')) ?? [];
//
//        usort($slider, function ($a, $b) {
//            return $a->order - $b->order;
//        });

//        return MainSliderResource::collection($slider);
        return response()->json('', 204);
    }

    public function add(Request $request)
    {
        $slider = Slider::create([
           'post_id' =>  $request->id
        ]);

//        $slider = Slider::select(['id', 'post_id', 'img', 'order'])
//            ->orderBy('order')
//            ->with('post:id,excerpt,title,img')
//            ->paginate(5);

        $slider = Slider::select(['id', 'post_id', 'img', 'order'])
            ->where('id',$slider->id)
            ->with('post:id,excerpt,title,img')
            ->first();

        return new MainSliderResource($slider);
//        $post = Post::select(['id','slug','title','excerpt','img'])->with('tags')->find($request->id);
//        $post['order'] = 0;
//
//        $data = json_decode(Redis::get('main-slider')) ?? [];
//        $data[] = $post;
//
//        Redis::set('main-slider', json_encode($data));
//
//        usort($data, function ($a, $b) {
//            return $a->order - $b->order;
//        });
//
//        return MainSliderResource::collection($data);
    }

    public function upload(Request $request)
    {
        $slide = Slider::find($request['id']);
        $this->setImage($request->file('img'), 'slider', $slide->img);
        $slide->update([
           'img' => $this->uploadImageForSlide()
        ]);

        return response()->json('',204);
    }

    public function delete($id) {
//        Slider::where('id', $id)->delete();
        $user=Slider::findOrFail((int)$id);
        $user->delete(); //returns true/false

        return response()->json('',204);
    }
}
