<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Filters\ArtistFilter;
use App\Models\Artist;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\GetArtistIndexRequest;
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
}
