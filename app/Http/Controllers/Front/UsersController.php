<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\AddArtistArtwork;
use App\Http\Requests\Front\LoginUserRequest;
use App\Http\Requests\Front\StoreArtistRequest;
use App\Http\Requests\Front\StoreOrUpdateUserRequest;
use App\Http\Requests\Front\UpdateArtistRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function register(StoreOrUpdateUserRequest $request)
    {
        $input = $request->validated();

        if (isset($input['email'])) {
            $existing = User::where('email', $input['email'])->first();
            if ($existing) {
                return response()->json(
                    [
                        'message' => 'Переданный адрес электронной почты уже используется',
                        "errors" =>  [
                            "email" => [
                                'Переданный адрес электронной почты уже используется'
                            ]
                        ]
                    ],
                    422
                );
            }
        }

        $user = User::create($input);

        $user->assignRole('regular_user');

        $token = $user->createToken('user ' . $user->id)->plainTextToken;

        return response()->json(['user_id' => $user->id, 'token' => $token], 200);
    }

    public function show(Request $request)
    {
        $user = $request->user();

        $user->load(['artist', 'artist.artworks', 'artist.tags']);

        return response()->json(['user' => $user], 200);
    }

    public function update(StoreOrUpdateUserRequest $request)
    {

        $user = $request->user();

        $input = $request->validated();

        if ($input['email']) {
            $existing = User::where('email', $input['email'])->whereNot('id', $user->id)->first();
            if ($existing) {
                return response()->json(
                    [
                        'message' => 'Переданный адрес электронной почты уже используется',
                        "errors" =>  [
                            "email" => [
                                'Переданный адрес электронной почты уже используется'
                            ]
                        ]
                    ],
                    422
                );
            }
        }

        $user->update($input);

        return response()->json(['user' => $user], 200);
    }

    public function login(LoginUserRequest $request)
    {

        $user = User::where([
            'email' => $request->email
        ])->first();

        if (!$user) {
            return response()->json(
                [
                    'message' => 'Пользователь не найден',
                    "errors" =>  [
                        "email" => [
                            'Пользователь с указанным адресом электронной почты не найден'
                        ]
                    ]
                ],
                422
            );
        }

        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('user ' . $user->id)->plainTextToken;
            return response()->json(['user_id' => $user->id, 'token' => $token], 200);
        } else {
            return response()->json(
                [
                    'message' => 'Пароль неверный',
                    "errors" =>  [
                        "email" => [
                            'Указан неверный пароль'
                        ]
                    ]
                ],
                422
            );
        }
    }

}
