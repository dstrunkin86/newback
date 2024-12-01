<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ArtistFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArtistRequest;
use App\Http\Requests\StoreImageRequest;
use App\Models\Artist;
use Illuminate\Support\Facades\Auth;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArtistFilter $filter)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        // return Artist::with(['artworks','user','tags:id,type,title'])->filter($filter)->get();
        return Artist::with(['artworks','user','tags:id,type,title'])->filter($filter)->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArtistRequest $request)
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
        $artist = Artist::create($request->validated());
        if (count($tags)>0) $artist->tags()->sync($tags);

        return $artist;
    }


    /**
     * Adds resource image.
     */
    public function addImage($artworkId, StoreImageRequest $request)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        $artist = Artist::findOrFail($artworkId);

        return $artist->addImage($request);
    }

    /**
     * Deletes resource image.
     */
    public function deleteImage($artistId, $imageId)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        $artist = Artist::findOrFail($artistId);

        return $artist->deleteImage($imageId);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Artist::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreArtistRequest $request, string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        $data = $request->validated();
        $artist = Artist::with(['tags'])->find($id);
        if (isset($data['tags'])) {
            $artist->tags()->sync($data['tags']);
            unset($data['tags']);
        }

        return $artist->update($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Artist::where('id',$id)->delete();
    }
}
