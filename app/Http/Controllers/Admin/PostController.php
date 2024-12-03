<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Post::orderBy('created_at','desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        return Post::query()->create($request->validated());
    }

    /**
     * Adds resource image.
     */
    public function addImage($postId, StoreImageRequest $request)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        $artwork = Post::findOrFail($postId);

        return $artwork->addImage($request);
    }

    /**
     * Deletes resource image.
     */
    public function deleteImage($postId, $imageId)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        $artwork = Post::findOrFail($postId);

        return $artwork->deleteImage($imageId);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        return Post::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePostRequest $request, string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        return Post::where('id',$id)->find($id)->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Post::findOrFail($id)->delete();
    }
}
