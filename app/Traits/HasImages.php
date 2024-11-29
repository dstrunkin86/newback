<?php

namespace App\Traits;

use App\Http\Requests\StoreImageRequest;
use App\Models\Image;
use Intervention\Image\Facades\Image as ImageLib;

trait HasImages
{

    public function initializeHasImages()
    {
        $this->appends[] = 'images';
    }

    public function updateImages($files)
    {
        //dd($files);
        Image::where([
            ['model_type', get_class($this)],
            ['model_id', $this->id]
        ])->delete();
        foreach ($files as $file) {
            $file_params = [
                'model_type' => get_class($this),
                'model_id' => $this->id,
                'url' => $file['url'],
                'priority' => (isset($file['priority'])) ? $file['priority'] : 0,
                'width' => (isset($file['width'])) ? $file['width'] : null,
                'height' => (isset($file['height'])) ? $file['height'] : null,
            ];
            //dd($file_params);
            Image::create($file_params);
        }
    }

    public function addImage($request)
    {
        if ($request->file('file') != '') {
            $fileName = microtime(true) . '.' . $request->file('file')->getClientOriginalExtension();
            $filePath = storage_path('app/public/images/' . $fileName);
            $url = '/storage/images/' . $fileName;
            $img = ImageLib::make($request->file('file'));
            $img->save($filePath, 90);

            $file_params = [
                'model_type' => get_class($this),
                'model_id' => $this->id,
                'url' => $url,
                'priority' => (isset($request['priority'])) ? $request['priority'] : 0,
                'width' => $img->width(),
                'height' => $img->height(),
            ];
            Image::create($file_params);

            $result = ["url" => $url];
            $code = 200;
        } else {
            $result = 'error';
            $code = 500;
        }

        return response()->json($result, $code);
    }

    public function deleteImage($id)
    {
        $image = Image::where([
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'id' => $id
        ])->first();

        if ($image) {
            $file = str_replace('/storage/images/', '', $image->url);
            if (file_exists(storage_path('app/public/images/' . $file))) {
                unlink(storage_path('app/public/images/' . $file));
            }
            $image->delete();

            $result = ["status" => 'success'];
            $code = 200;
        } else {
            $result = ["status" => 'error'];
            $code = 500;
        }

        return response()->json($result, $code);
    }

    public function GetImagesAttribute()
    {
        return Image::where([
            ['model_type', get_class($this)],
            ['model_id', $this->id]
        ])->orderBy('priority', 'asc')
            ->get()
            ->toArray();
    }
}
