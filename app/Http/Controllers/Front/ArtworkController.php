<?php

namespace App\Http\Controllers\Front;

use App\Filters\ArtworkFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\AddArtistArtwork;
use App\Http\Requests\Front\BuyArtworkRequest;
use App\Http\Requests\Front\GetArtworkIndexRequest;
use App\Http\Requests\Front\GetDeliveryCostRequest;
use App\Http\Requests\Front\GetDeliveryOptionsRequest;
use App\Models\Artwork;
use App\Models\Order;
use App\Services\Delivery\SdekService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ArtworkController extends Controller
{
    /**
     * Display artworks list.
     */
    public function index(GetArtworkIndexRequest $request, ArtworkFilter $filter) {

        $pageSize = (isset($request->page_size)) ? $request->page_size : 12;

        $sortField = (isset($request->sort_field)) ? $request->sort_field : 'id';

        $sortOrder = (isset($request->sort_order)) ? $request->sort_order : 'desc';

        $result = Artwork::query()->with(['artist'])->where('status','accepted')->filter($filter);

        switch ($sortField) {
            case 'price':
                $result = $result->orderBy('in_sale','desc')->orderBy('price',$sortOrder);
                break;
            case 'random':
                $result = $result->inRandomOrder();
                break;
            default:
            $result = $result->orderBy($sortField,$sortOrder);
        }

        $result = $result->paginate($pageSize);

        return $result;
    }

    /**
     * Display Google merchant list.
     */
    public function googleMerchant() {

        $result = Artwork::query()->with('artist')->where('status','accepted')->where('in_sale',1)->where('price','>',0)->get();

        $data['artworks'] = $result;

        $content = View::make('xml.google-merchant',$data);
        return response($content,200,)->header('Content-Type', 'application/xml');
    }

    /**
     * Display the specified artist.
     */
    public function show($id)
    {
        $artwork = Artwork::with(['artist.accepted_artworks','tags:id,type,title','compilations'])->findOrFail($id)->append('similar_paintings');
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
                'order_id' => $order->id,
                'success' => true,
                'payment_confirmation_id' => $request->payment_confirmation_id
            ],200);

        } else {
            return response()->json(['error' => 'У работы не заданы обязательные параметры'],500);
        }

    }

    public function addArtistArtwork(AddArtistArtwork $request)
    {
        $user = $request->user();

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

        $artwork = $user->artist->artworks()->create($data);

        if (count($tags) > 0) $artwork->tags()->sync($tags);

        if (count($images) > 0) $artwork->updateImages($images);

        $artwork->load('tags');
        $artwork->refresh();

        return response()->json(['artwork' => $artwork], 200);
    }

    public function deleteArtistArtwork(Request $request, $artworkId)
    {
        $user = $request->user();

        $artwork = $user->artist->artworks()->findOrFail($artworkId);

        if (count($artwork->orders()->whereNotIn('status',['cancelled_by_user','cancelled_by_system'])->get())>0) {
            return response()->json(
                [
                    'message' => 'У работы есть активные заказы. Для удаления свяжитесь с менеджером',
                    "errors" =>  [
                        [
                            'У работы есть активные заказы. Для удаления свяжитесь с менеджером'
                        ]
                    ]
                ],
                403
            );
        }
        if (count($artwork->compilations)>0) {
            return response()->json(
                [
                    'message' => 'У работы есть активные подборки. Для удаления свяжитесь с менеджером',
                    "errors" =>  [
                        [
                            'У работы есть активные подборки. Для удаления свяжитесь с менеджером'
                        ]
                    ]
                ],
                403
            );
        }
        $artwork->delete();

        return response()->json(['status' => 'success'],200);

    }
    //TODO: нужна команда каждую ночь, которая будет удалять картинки, которые нигде не используются. При этом, надо смотреть, чтобы у deleted_at сущностей картинки не удалялись

}
