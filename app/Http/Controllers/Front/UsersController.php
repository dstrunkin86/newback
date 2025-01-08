<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\LoginUserRequest;
use App\Http\Requests\Front\StoreArtistRequest;
use App\Http\Requests\Front\StoreOrUpdateUserRequest;
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

        $user->load(['artist','artist.artworks']);

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

    public function registerArtist(StoreArtistRequest $request) {
        // проверить email и пароль
        $user = $request->user();

        if ($user->artist) {
            return response()->json(
                [
                    'message' => 'У пользователя уже есть учетная запись автора',
                    "errors" =>  [
                        [
                            'У пользователя уже есть учетная запись автора'
                        ]
                    ]
                ],
                403
            );
        }

        if (($user->password == NULL) || ($user->email == NULL)) {
            return response()->json(
                [
                    'message' => 'У пользователя не задан email или пароль',
                    "errors" =>  [
                        "email" => [
                            'У пользователя не задан email'
                        ],
                        "password" => [
                            'У пользователя не задан пароль'
                        ]
                    ]
                ],
                422
            );
        }

        $data = $request->validated();

        // сохранить данные
        $tags = [];
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        $data['email'] = $user->email;

        $artist = $user->artist()->create($data);
        if (count($tags)>0) $artist->tags()->sync($tags);
        if (count($data['images'])>0) $artist->updateImages($data['images']);
        if (count($data['artworks'])>0) {
            foreach ($data['artworks'] as $incoming_artwork) {
                $artwork = $artist->artworks()->create($incoming_artwork);
                $artwork->updateImages($incoming_artwork['images']);
            }
        }

        // поднять роль
        $user->assignRole('artist');
        $user->refresh();
        $user->load(['artist','artist.artworks']);


        return response()->json(['user' => $user], 200);

    }

    // public function updateArtist(UpdateArtistRequest $request) {
    //     // проверить email и пароль
    //     $user = $request->user();

    //     if (!$user->artist) {
    //         return response()->json(
    //             [
    //                 'message' => 'У пользователя нет учетной записи автора',
    //                 "errors" =>  [
    //                     [
    //                         'У пользователя нет учетной записи автора'
    //                     ]
    //                 ]
    //             ],
    //             403
    //         );
    //     }

    //     $data = $request->validated();

    //     // сохранить данные
    //     $tags = [];
    //     if (isset($data['tags'])) {
    //         $tags = $data['tags'];
    //         unset($data['tags']);
    //     }

    //     $data['email'] = $user->email;

    //     $artist = $user->artist()->update($request->validated());
    //     if (count($tags)>0) $artist->tags()->sync($tags);

    //     // поднять роль
    //     $user->assignRole('artist');

    // }

    // Route::middleware('auth:sanctum')->post('/users/artist', [FrontUsersController::class, 'registerArtist']);
    // Route::middleware('auth:sanctum')->patch('/users/artist', [FrontUsersController::class, 'updateArtist']);
    // Route::middleware('auth:sanctum')->get('/users/orders', [FrontUsersController::class, 'userArtistOrders']);
}
