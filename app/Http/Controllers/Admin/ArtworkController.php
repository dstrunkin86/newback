<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ArtworkFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArtworkRequest;
use App\Http\Requests\StoreImageRequest;
use App\Models\Artwork;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArtworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArtworkFilter $filter)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Artwork::with(['artist','tags:id,type,title','compilations:id,title'])->filter($filter)->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArtworkRequest $request)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        $data = $request->validated();
        $tags = [];
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }
        $compilations = [];
        if (isset($data['compilations'])) {
            $compilations = $data['compilations'];
            unset($data['compilations']);
        }
        $artwork = Artwork::query()->create($data);
        if (count($tags)>0) $artwork->tags()->sync($tags);
        if (count($compilations)>0) $artwork->compilations()->sync($compilations);

        return $artwork;
    }

    /**
     * Adds resource image.
     */
    public function addImage($artworkId, StoreImageRequest $request)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        $artwork = Artwork::findOrFail($artworkId);

        return $artwork->addImage($request);
    }

    /**
     * Deletes resource image.
     */
    public function deleteImage($artworkId, $imageId)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        $artwork = Artwork::findOrFail($artworkId);

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
        return Artwork::with(['artist','tags:id,type,title','compilations:id,title'])->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreArtworkRequest $request, string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        $data = $request->validated();

        $artwork = Artwork::with(['tags','compilations'])->find($id);
        if (isset($data['tags'])) {
            $artwork->tags()->sync($data['tags']);
            unset($data['tags']);
        }
        if (isset($data['compilations'])) {
            $artwork->compilations()->sync($data['compilations']);
            unset($data['compilations']);
        }

        return $artwork->update($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Artwork::findOrFail($id)->delete();
    }
}
