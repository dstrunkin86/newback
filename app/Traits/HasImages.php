<?php

namespace App\Traits;

use App\Models\Image;

trait HasImages
{

    public function initializeHasImages()
    {
        $this->appends[] = 'images';
    }

    public function updateImages($files) {
        Image::where([
            ['model_type',get_class($this)],
            ['model_id',$this->id]
        ])->delete();
        foreach($files as $file) {
            $file_params = [
                'model_type' => get_class($this),
                'model_id' => $this->id,
                'url' => $file['url'],
                'priority' => (isset($file['priority'])) ? $file['priority']:0,
                'width' => (isset($file['width'])) ? $file['width']:null,
                'height' => (isset($file['height'])) ? $file['height']:null,
            ];
            Image::create($file_params);
        }
    }

    public function GetImagesAttribute() {
        return Image::where([
            ['model_type',get_class($this)],
            ['model_id',$this->id]
        ])->orderBy('priority','asc')
        ->get()
        ->toArray();
    }

}
