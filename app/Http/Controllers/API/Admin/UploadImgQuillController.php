<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadImgQuillController extends Controller
{
    use ImageUploadTrait;
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        if ($request['delete_file']) {
            $this->deleteImage('articles/'.$request['delete_file']);
            return response()->json(null, 204);
        }

        $fileNAme = $this->setImage('img', 'articles');
        $this->uploadImage();

        return response()->json(Storage::url('articles/'.$fileNAme));
    }
}
