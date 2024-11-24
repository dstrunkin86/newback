<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompilationRequest;
use App\Services\Admin\CompilationService;
use Illuminate\Support\Facades\Auth;

class CompilationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CompilationService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompilationRequest $request, CompilationService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, CompilationService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCompilationRequest $request, string $id, CompilationService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, CompilationService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->delete($id);
    }
}
