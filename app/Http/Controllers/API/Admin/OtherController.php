<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Resources\Slider\MainSliderResource;
use App\Models\Slider;
use App\Repositories\Contracts\IOther;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class OtherController extends AdminController
{
    use ImageUploadTrait;

    protected $slider;

    public function __construct()
    {
        parent::__construct();
        $this->slider = app(IOther::class);
    }

    public function index(Request $request)
    {
        $check = $request->query('limit') !== "all";

        $slider = $this->slider->getPosts($check);

        return response()->json([
            'data' => MainSliderResource::collection($slider),
            'count' => Slider::count()
        ]);
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

        return response()->json('', 204);
    }

    public function add(Request $request)
    {
        $slider = Slider::create([
           'post_id' =>  $request->id
        ]);

        $slider = $this->slider->getPost($slider->id);

        return new MainSliderResource($slider);
    }

    public function upload(Request $request)
    {
        $slide = $this->slider->find($request['id']);
        $this->setImage('img', 'slider', $slide->img);
        $slide->update([
           'img' => $this->uploadImageForSlide()
        ]);

        return response()->json('',204);
    }

    public function delete($id) {
        $user = $this->slider->delete((int)$id);

        return response()->json('',204);
    }
}
