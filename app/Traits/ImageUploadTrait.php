<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ImageUploadTrait
{

    private $fileName;
    private $image;
    private $folder;
    private $oldImage;

    public function setImage(UploadedFile $uploadedFile, $folder, $oldImage = null): string
    {
        $this->fileName = $this->imageName($uploadedFile);
        $this->image = $uploadedFile;
        $this->folder = $folder;
        $this->oldImage = $oldImage;

        return $this->fileName;
    }

    public function uploadImage()
    {
        return $this->image->storeAs($this->folder, $this->fileName);
    }

    public function updateImage()
    {
        if($this->fileName) {
            Storage::delete($this->oldImage);
            return $this->uploadImage();
        }
    }

    public function uploadAvatar()
    {
        if($this->fileName) {
            return $this->uploadImage();
        }
    }

    public function updateAvatar()
    {
        if($this->fileName) {
            if ($this->oldImage != 'avatar.png') {
                Storage::delete($this->oldImage);
            }

            return $this->uploadImage();
        }
    }

    public function imageName($image)
    {
        $fileName = null;

        if($image) {
            $replaceRuWords = Str::slug($image->getClientOriginalName(), '_');
            $strtolower = strtolower($replaceRuWords);
            $replaceSpaces = preg_replace('/\s+/', '_', $strtolower);
            $fileName = time().'_'.$replaceSpaces;
        }

        return $fileName;
    }

//    private function storeImage($post)
//    {
//
//        if (request()->hasFile('image')){
//            $image_path = "/storage/".'prev_img_name';  // prev image path
//            if(File::exists($image_path)) {
//                File::delete($image_path);
//            }
//            $post->update([
//                'image' => request()->image->store('uploads', 'public'),
//            ]);
//
//            $image = Image::make(public_path('storage/'.$post->image))->fit(750, 300);
//            $image->save();
//        }
//    }
}
