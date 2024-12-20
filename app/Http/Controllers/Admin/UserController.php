<?php

namespace App\Http\Controllers\Admin;

use App\Filters\UserFilter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserFilter $filter)
    {
        if (!(Auth::user()->hasRole('admin'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return User::with(['artist'])->filter($filter)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        if (!(Auth::user()->hasRole('admin'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        $data = $request->validated();
        $user = User::query()->create($data);
        $user->syncRoles($data['role']);
        return $user;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!(Auth::user()->hasRole('admin'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return User::query()->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, string $id)
    {
        if (!(Auth::user()->hasRole('admin'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        $data = $request->validated();
        $user = User::find($id);
        $user->syncRoles($data['role']);
        $user = $user->update($data);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!(Auth::user()->hasRole('admin'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return User::findOrFail($id)->delete();
    }
}
