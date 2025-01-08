<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Filters\ArtistFilter;
use App\Models\Artist;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\GetArtistIndexRequest;
use App\Http\Requests\Front\StoreArtistRequest;
use App\Http\Requests\Front\UpdateArtistRequest;
use Illuminate\Support\Facades\DB;

class ArtistController extends Controller
{
    /**
     * Display artist list.
     */
    public function index(GetArtistIndexRequest $request, ArtistFilter $filter) {

        $pageSize = (isset($request->page_size)) ? $request->page_size : 12;

        $sortField = (isset($request->sort_field)) ? $request->sort_field : 'id';

        $sortOrder = (isset($request->sort_order)) ? $request->sort_order : 'desc';

        $result = Artist::query()->where('status','accepted')->filter($filter)->orderBy($sortField,$sortOrder)->paginate($pageSize);
        return $result;
    }

    /**
     * Display the specified artist.
     */
    public function show(string $id)
    {
        $artist = Artist::with(['accepted_artworks','tags:id,type,title'])->where('url',$id)->first();
        if (!$artist) {
            $artist = Artist::with(['accepted_artworks','tags:id,type,title'])->findOrFail($id);
        }
        return $artist;
    }

    public function registerArtist(StoreArtistRequest $request)
    {
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
        if (count($tags) > 0) $artist->tags()->sync($tags);
        if (count($data['images']) > 0) $artist->updateImages($data['images']);
        if (count($data['artworks']) > 0) {
            foreach ($data['artworks'] as $incoming_artwork) {
                $artwork = $artist->artworks()->create($incoming_artwork);
                $artwork->updateImages($incoming_artwork['images']);
            }
        }

        // поднять роль
        $user->assignRole('artist');
        $user->refresh();
        $user->load(['artist', 'artist.artworks']);


        return response()->json(['artist' => $user->artist], 200);
    }

    public function updateArtist(UpdateArtistRequest $request)
    {
        $user = $request->user();

        if (!$user->artist) {
            return response()->json(
                [
                    'message' => 'У пользователя нет учетной записи автора',
                    "errors" =>  [
                        [
                            'У пользователя нет учетной записи автора'
                        ]
                    ]
                ],
                403
            );
        }

        $artist = $user->artist;

        $data = $request->validated();

        // сохранить данные
        $tags = [];
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        $images = [];
        if (isset($data['images'])) {
            $images = $data['images'];
            unset($data['images']);
        }

        $artist->update($data);

        if (count($tags) > 0) $artist->tags()->sync($tags);

        if (count($images) > 0) $artist->updateImages($images);

        $artist->refresh();

        return response()->json(['artist' => $artist], 200);
    }
}
