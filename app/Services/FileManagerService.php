<?php

namespace App\Services;
use App\Models\ImageManager;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class FileManagerService
{
    private $supportedMimes = ['png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG', 'gif', 'GIF', 'webp', 'WEBP'];
    private $extension = 'webp';

    /**
     * Generate unique file name.
     */
    private function generateUniqueFileName($extension)
    {
        return uniqid() . '.' . $extension;
    }

    /**
     * Checking file extension mime.
     *
     * @param  String  $extension
     *
     * @return bool
     */
    public function checkExtension($fileName):bool
    {
        if (!in_array($fileName->getClientOriginalExtension(), $this->supportedMimes)) {
            return false;
        }
        return true;
    }

    /**
     * @param  String  $name
     * @param  String  $croppedData ,base64
     * @param  String  $folderName
     * @param  ImageManager $imageManager
     * @param  String  $specificExtension
     *
     * @return String
     */
    public function uploadImage($name, $croppedData, $folderName, $imageManager, $specificExtension = null):String
    {
        /**
         * If there is a spesific mime use that.
         */
        if($specificExtension){
            $this->extension = $specificExtension;
        }

        $randName = $this->generateUniqueFileName($this->extension);
        //Saving base64 data.
        $base64Str = substr($croppedData, strpos($croppedData, ",") + 1);
        $file = base64_decode($base64Str);
        $fullPath = 'uploads/media/'.$folderName.'/'.$randName;     // For Database full file path.
        //Intervention
        $file_name = $randName;
        $destinationPath = 'uploads/media/'.$folderName.'/';        // Folder Path
        $new_img = Image::make($file)->resize($imageManager->width, $imageManager->height)->encode($this->extension);
        $new_img->save($destinationPath . $file_name, $imageManager->quality, $this->extension);

        /**
         * Validation for file size.
         */
        if (File::size($destinationPath . $file_name) > $imageManager->file_size) {
            unlink($destinationPath . $file_name);
            return '0';
        }
        return $fullPath;
    }
}
