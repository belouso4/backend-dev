<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait ImageUploadTrait
{
    private $fileName;
    private $image;
    private $folder;
    private $oldImage;

    public function setImage($file, $folder, $oldImage = null) : ?string
    {
        if (!request()->hasFile($file)) return null;

        $this->image = request()->file($file);
        $this->fileName = $this->imageName($this->image);

        $this->folder = $folder;
        $this->oldImage = $oldImage;

        return $this->fileName;
    }

    public function uploadImage()
    {
        return $this->image->storeAs($this->folder, $this->fileName);
    }

    public function uploadAvatarImage()
    {
        $destinationPath = Storage::path($this->folder).'/small/'. $this->fileName;
        $thumbnail = Image::make($this->image->getRealPath());
        $thumbnail->fit(100, 100);
        $thumbnail->save($destinationPath);
        $this->image->storeAs($this->folder.'/original', $this->fileName);

//        return $this->image->storeAs($this->folder, $this->fileName);
        return $this->fileName;
    }

    public function updateImage()
    {
        if($this->fileName) {
            if ($this->oldImage != '300x200.png') {
                Storage::delete($this->oldImage);
            }
            return $this->uploadImage();
        }
    }

    public function uploadImageForSlide()
    {
        if($this->fileName) {
            if ($this->oldImage) {
                Storage::delete($this->oldImage);
            }

            return $this->uploadImage();
        }
    }

    public function uploadAvatar()
    {
        if($this->fileName) {
            return $this->uploadAvatarImage();
        }
    }

    public function updateAvatar()
    {
        if($this->fileName) {
            if ($this->oldImage != 'avatar.png') {
                Storage::delete($this->folder.'/original/'.$this->oldImage);
                Storage::delete($this->folder.'/small/'.$this->oldImage);
            }

            return $this->uploadAvatarImage();
        }
    }

    public function imageName(UploadedFile $image)
    {
        if (!$image) return $image;

        $baseName = basename(
            $image->getClientOriginalName(),
            '.'.$image->getClientOriginalExtension()
        );
        $replaceWords = Str::slug($baseName,'_');
        $fileName = time().'_'.$replaceWords. '.' .$image->getClientOriginalExtension();

        return $fileName;
    }

    public function deleteImage($filePath)
    {
        Storage::delete($filePath);
    }
}
