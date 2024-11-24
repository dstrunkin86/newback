<?php

namespace App\Http\Controllers\Admin;

use App\Filters\Admin\UserFilter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Services\Admin\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserFilter $filter, UserService $service)
    {
        if (!(Auth::user()->hasRole('admin'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->index($filter);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, UserService $service)
    {
        if (!(Auth::user()->hasRole('admin'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, UserService $service)
    {
        if (!(Auth::user()->hasRole('admin'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, string $id, UserService $service)
    {
        if (!(Auth::user()->hasRole('admin'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, UserService $service)
    {
        if (!(Auth::user()->hasRole('admin'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->delete($id);
    }
}
