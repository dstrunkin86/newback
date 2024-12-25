<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Returns the tree of available tags.
     */
    public function tagsTree() {
        $tags = Tag::get(['id','type','title'])->toArray();
        $structure = [];
        foreach ($tags as $tag) {
            $structure[$tag['type']][] = $tag;
        }
        return $structure;
    }

    /**
     * Returns the list of available cities with artists
     */
    public function artistCities() {
        $rawQuery = 'select DISTINCT(JSON_UNQUOTE(JSON_EXTRACT(city, "$.value"))) AS city from artists where city IS NOT NULL AND status = "accepted" AND deleted_at IS NULL';
        $result = DB::select($rawQuery);
        return Arr::pluck($result,'city');
    }

    /**
     * Returns the list of available cities with artworks
     */
    public function artworkCities() {
        $rawQuery = 'select DISTINCT(JSON_UNQUOTE(JSON_EXTRACT(location, "$.city"))) AS city from artworks where location IS NOT NULL AND status = "accepted" AND deleted_at IS NULL';
        $result = DB::select($rawQuery);
        return Arr::pluck($result,'city');
    }


}
