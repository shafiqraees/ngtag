<?php

namespace App\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait StorageTrait
{
    protected $disk_options = ['visibility' => Filesystem::VISIBILITY_PUBLIC];
    public function saveFile(string $file, $dir = null, $file_name){
        if( empty($file) ) return null;
        // this is to prevent urls from getting passed to decoding section
        if ( filter_var($file , FILTER_VALIDATE_URL) ) return $file;
        /*$base64Image = explode(";base64,", $file);
        $explodeImage = explode("image/", $base64Image[0]);
        $imageName = $explodeImage[1];
        $image_base64 = base64_decode($base64Image[1]);*/
        $_file = $this->base64FileAndName($file,$file_name);
        $path =  !empty($dir) ? "$dir/".$_file['name'] : $_file['name'];
        Storage::disk(config('filesystems.default'))
            ->put($path, $_file['file'], ['visibility' => Filesystem::VISIBILITY_PUBLIC]);
        return "/" . $path;
    }

    public function base64FileAndName($_file,$file_name, $use_uniq_id = true, $namePrefix = ''){

        list($type, $file) = explode(';', $_file);
        list(, $extension) = explode('/', $type);
        list(, $file) = explode(',', $file);
        if( $use_uniq_id){ $namePrefix = request()->input('msisdn')."_".$file_name."_".$namePrefix; }
        $result['name'] = $namePrefix . '.' . $extension;
        $result['file'] = base64_decode($file);
        return $result;
    }

    // Extension is missing...
    public function saveFileWithThumb(string $base64_image, string $dir, $visibility = 'public', $only_resize = false, $resize = false, $resize_options = ['width' => 32, 'height' => 32]){

        $image_base_name = uniqid();
        if( !$only_resize ) {
            $original_image = Image::make($base64_image);
            $original_image->response('png');

            $image_name = $image_base_name . '_original.png';

            Storage::disk(config('filesystems.default_upload_disk'))->put($dir . $image_name, $original_image, $visibility);
        }

        if( $resize ) {
            $resized_image = Image::make($base64_image)->resize($resize_options['width'], $resize_options['height']);
            $resized_image->response('png');

            $resize_name = $image_base_name."_".$resize_options['width']."x".$resize_options['height'].".png";

            Storage::disk(config('filesystems.default_upload_disk'))->put('thumb/' .$dir . $resize_name, $resized_image, $visibility);
        }

    }



}
