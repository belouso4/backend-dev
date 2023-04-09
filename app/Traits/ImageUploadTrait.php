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

    public function setImage( ?UploadedFile $uploadedFile, $folder, $oldImage = null)
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
            if ($this->oldImage != '300x200.png') {
                Storage::delete($this->oldImage);
            }
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
            $replaceRuWords = self::translit($image->getClientOriginalName(), '_');
            $strToLower = strtolower($replaceRuWords);
            $replaceSpaces = preg_replace('/\s+/', '_', $strToLower);
            $fileName = time().'_'.$replaceSpaces;
        }

        return $fileName;
    }

    public static function translit($str)
    {
        $tr = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
            "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
            "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"'","э"=>"e","ю"=>"yu","я"=>"ya",
            " "=>"_","?"=>"_","/"=>"_","\\"=>"_",
            "*"=>"_",":"=>"_","*"=>"_","\""=>"_","<"=>"_",
            ">"=>"_","|"=>"_"
        );
        return strtr($str,$tr);
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
