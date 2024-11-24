<?php

namespace App\Http\Controllers\Admin;

use App\Filters\Admin\ArtistFilter;
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
        return Artist::with(['artworks','user'])->filter($filter)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArtistRequest $request)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Artist::create($request->validated());
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
        return Artist::where('id', $id)->update($request->validated());
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
