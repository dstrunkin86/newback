<?php

namespace App\Http\Controllers\Admin;

use App\Filters\Admin\ArtworkFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArtworkRequest;
use App\Services\Admin\ArtworkService;
use Illuminate\Support\Facades\Auth;

class ArtworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArtworkFilter $filter, ArtworkService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->index($filter);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArtworkRequest $request, ArtworkService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ArtworkService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreArtworkRequest $request, string $id, ArtworkService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ArtworkService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->delete($id);
    }
}
