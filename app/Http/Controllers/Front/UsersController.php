<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\LoginUserRequest;
use App\Http\Requests\Front\StoreOrUpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function register(StoreOrUpdateUserRequest $request) {
        $input = $request->validated();

        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $user = User::create($input);

        $user->assignRole('regular_user');

        $token = $user->createToken($user->id)->plainTextToken;

        return response()->json(['user_id' => $user->id,'token' => $token], 200);
    }

    public function update(StoreOrUpdateUserRequest $request) {

        $user = $request->user();

        $input = $request->validated();

        if ($input['password']) {
            $input['password'] = Hash::make($input['password']);
        }

        $user->update($input);

        return response()->json(['user' => $user], 200);

    }

    public function login(LoginUserRequest $request) {

        $user = User::where([
            'email' => $request->email,
            'password' => Hash::make($request['password'])
        ])->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 422);
        }

        $token = $user->createToken($user->device_name)->plainTextToken;

        return response()->json(['user_id' => $user->id,'token' => $token], 200);

    }

}
