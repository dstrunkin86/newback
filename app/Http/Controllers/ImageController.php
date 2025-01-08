<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageRequest $request)
    {
        // if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator') || Auth::user()->hasRole('artist'))){
        //     return response()->json(['message' => 'Нет доступа'],401);
        // }

        if ($request->file('file') != '') {
            $fileName = microtime(true). '.' . $request->file('file')->getClientOriginalExtension();
            $filePath = storage_path('app/public/images/'. $fileName);
            $url = '/storage/images/' . $fileName;

            $img = Image::make($request->file('file'));

            $img->save($filePath, 90);

            $result = ["url" => $url];
            $code = 200;
          } else {
            $result = 'error';
            $code = 500;
          }

          return response()->json($result, $code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator') || Auth::user()->hasRole('artist'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        $file = str_replace('/storage/images/','',$request->url);
        if (file_exists(storage_path('app/public/images/'. $file))) {
            unlink(storage_path('app/public/images/'. $file));
        }

        return response()->json(['status' => 'success'],200);
    }
}
