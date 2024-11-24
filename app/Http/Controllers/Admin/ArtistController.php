<?php

namespace App\Http\Controllers\Admin;

use App\Filters\Admin\ArtistFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArtistRequest;
use App\Services\Admin\ArtistService;
use Illuminate\Support\Facades\Auth;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArtistFilter $filter, ArtistService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->index($filter);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArtistRequest $request, ArtistService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ArtistService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreArtistRequest $request, string $id, ArtistService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ArtistService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->delete($id);
    }
}
