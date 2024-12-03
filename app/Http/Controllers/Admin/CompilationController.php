<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompilationRequest;
use App\Models\Compilation;
use Illuminate\Support\Facades\Auth;

class CompilationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Compilation::with(['artworks'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompilationRequest $request)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Compilation::query()->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Compilation::query()->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCompilationRequest $request, string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Compilation::query()->where('id', $id)->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Compilation::findOrFail($id)->delete();
    }
}
