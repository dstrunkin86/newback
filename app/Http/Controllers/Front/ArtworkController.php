<?php

namespace App\Http\Controllers\Front;

use App\Filters\ArtworkFilter;
use App\Http\Controllers\Controller;
use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtworkController extends Controller
{
    /**
     * Display artworks list.
     */
    public function index(Request $request, ArtworkFilter $filter) {

        $pageSize = (isset($request->page_size)) ? $request->page_size : 12;

        $sortField = (isset($request->sort_field)) ? $request->sort_field : 'id';

        $sortOrder = (isset($request->sort_order)) ? $request->sort_order : 'desc';

        // DB::enableQueryLog();
        $result = Artwork::query()->where('status','accepted')->filter($filter)->orderBy($sortField,$sortOrder)->paginate($pageSize);
        // dd(DB::getQueryLog());
        return $result;
    }

    /**
     * Display the specified artist.
     */
    public function show(string $id)
    {
        $artwork = Artwork::with(['artist','tags:id,type,title','compilations'])->findOrFail($id);
        return $artwork;
    }
}
