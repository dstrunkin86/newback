<?php

namespace App\Http\Controllers\Front;

use App\Filters\ArtworkFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\BuyArtworkRequest;
use App\Http\Requests\Front\GetArtworkIndexRequest;
use App\Http\Requests\Front\GetDeliveryCostRequest;
use App\Http\Requests\Front\GetDeliveryOptionsRequest;
use App\Models\Artwork;
use App\Models\Order;
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
     * Display delivery options.
     */
    public function getDeliveryOptions(GetDeliveryOptionsRequest $request, $id) {
        $artwork = Artwork::findOrFail($id);

        if (($artwork->width)&&($artwork->height)&&($artwork->depth)&&($artwork->weight)&&($artwork->location)) {
            return (new SdekService)->getDeliveryOptions($artwork->location->city, $request->recepient_address['city'], $artwork->width, $artwork->height, $artwork->depth, $artwork->weight);
        } else {
            return response()->json(['error' => 'У работы не заданы обязательные параметры'],500);
        }
    }

    /**
     * Display details for specific option.
     */
    public function getDeliveryCost(GetDeliveryCostRequest $request, $id) {
        $artwork = Artwork::findOrFail($id);

        if (($artwork->width)&&($artwork->height)&&($artwork->depth)&&($artwork->weight)&&($artwork->price)&&($artwork->location)) {
            return (new SdekService)->getDeliveryCost($request->option_code, $artwork->price, $artwork->location->city, $request->recepient_address['city'], $artwork->width, $artwork->height, $artwork->depth, $artwork->weight, $request->need_insurance);
        } else {
            return response()->json(['error' => 'У работы не заданы обязательные параметры'],500);
        }



    }

    /**
     * Display the form to buy an artwork.
     */
    public function buy(BuyArtworkRequest $request, $id) {
        $artwork = Artwork::findOrFail($id);
        $artist = $artwork->artist;

        if (($artwork->width)&&($artwork->height)&&($artwork->depth)&&($artwork->weight)&&($artwork->price)&&($artwork->location)) {
            //создаем новый заказ

            $order = Order::create([
                'artwork_id' => $artwork->id,
                'artwork_price' => $artwork->price,
                'recepient_address' => $request->recepient_address,
                'recepient_contact' => $request->recepient_contact,
                'insurance' => $request->need_insurance,
                'delivery_system' => 'sdek',
                'delivery_option' => $request->option_code
            ]);

            // создаем заявку на доставку

            $request = $order->createDeliveryRequest();

            if (!$request->success) {
                return response()->json([
                    'error' => $request->reason
                ],500);
            }

            // создаем платеж

            $request = $order->createPayment();

            if (!$request->success) {
                return response()->json([
                    'error' => $request->reason
                ],500);
            }

            return response()->json([
                'success' => true,
                'payment_confirmation_id' => $request->payment_confirmation_id
            ],200);

        } else {
            return response()->json(['error' => 'У работы не заданы обязательные параметры'],500);
        }

    }
}
