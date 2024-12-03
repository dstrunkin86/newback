<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json(['user_id' => $user->id,'token' => $token], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 422);
        }

        $user->fill($request->validated());
        $user->save();

        return response()->json(['user'=> $user], 200);
    }
}
