<?php

namespace App\Http\Controllers\Front;

use App\Filters\ArtworkFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\BuyArtworkRequest;
use App\Http\Requests\Front\GetArtworkIndexRequest;
use App\Http\Requests\Front\GetDeliveryCostsRequest;
use App\Models\Artwork;
use App\Services\Delivery\SdekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtworkController extends Controller
{
    /**
     * Display artworks list.
     */
    public function index(GetArtworkIndexRequest $request, ArtworkFilter $filter) {

        $pageSize = (isset($request->page_size)) ? $request->page_size : 12;

        $sortField = (isset($request->sort_field)) ? $request->sort_field : 'id';

        $sortOrder = (isset($request->sort_order)) ? $request->sort_order : 'desc';

        // DB::enableQueryLog();
        $result = Artwork::query()->with(['artist'])->where('status','accepted')->filter($filter)->orderBy($sortField,$sortOrder)->paginate($pageSize);
        // dd(DB::getQueryLog());
        return $result;
    }

    /**
     * Display the specified artist.
     */
    public function show($id)
    {
        $artwork = Artwork::with(['artist','tags:id,type,title','compilations'])->findOrFail($id);
        return $artwork;
    }

    /**
     * Display the specified artist.
     */
    public function getDeliveryCost(GetDeliveryCostsRequest $request, $id) {
        $artwork = Artwork::findOrFail($id);

        if (($artwork->width)&&($artwork->height)&&($artwork->depth)&&($artwork->weight)) {
            return (new SdekService)->getDeliveryCosts($request->from_index, $request->to_index, $artwork->width, $artwork->height, $artwork->depth, $artwork->weight);
        } else {
            return response()->json(['error' => 'У работы не заданы обязательные параметры'],500);
        }

    }

    /**
     * Display the form to buy an artwork.
     */
    public function buy(BuyArtworkRequest $request, $id) {
        $artwork = Artwork::findOrFail($id);

        if (($artwork->width)&&($artwork->height)&&($artwork->depth)&&($artwork->weight)) {
            // рассчитать выбранный тип доставки

            // сделать заказ

            // сделать холд на сумму картины + доставки




        } else {
            return response()->json(['error' => 'У работы не заданы обязательные параметры'],500);
        }

    }
}
