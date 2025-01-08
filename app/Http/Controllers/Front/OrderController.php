<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ArtistCallCourierRequest;
use App\Models\Order;
use App\Services\Delivery\SdekService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function artistOrdersList(Request $request) {
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

        $artist_id = $user->artist->id;

        $orders = Order::whereHas('artwork', function($query) use ($artist_id) {
            $query->where('artist_id', $artist_id );
            })->get();


        return response()->json($orders,200);
    }

    public function artistCallCourier(ArtistCallCourierRequest $request, $orderId) {
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

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(
                [
                    'message' => 'Заказ не найден',
                    "errors" =>  [
                        [
                            'Заказ не найден'
                        ]
                    ]
                ],
                404
            );
        }

        if (($order->artwork->artist_id != $user->artist->id) || ($order->status != "accepted_by_artist")) {
            return response()->json(
                [
                    'message' => 'Системная ошибка. Свяжитесь с менеджером',
                    "errors" =>  [
                        [
                            'Системная ошибка. Свяжитесь с менеджером'
                        ]
                    ]
                ],
                403
            );
        }

        return (new SdekService)->callCourier($request->delivery_date, $request->delivery_time_from, $request->delivery_time_to, $order->delivery_id, $order->artwork->artist->fio->ru, $order->artwork->artist->phone, $order->artwork->location->value, $request->courier_comment);

    }

    public function artistAcceptOrder(Request $request, $orderId) {
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

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(
                [
                    'message' => 'Заказ не найден',
                    "errors" =>  [
                        [
                            'Заказ не найден'
                        ]
                    ]
                ],
                404
            );
        }

        if (($order->artwork->artist_id != $user->artist->id) || ($order->status != "hold")) {
            // тут все возможные проверки на ошибки статусов и состояний
            return response()->json(
                [
                    'message' => 'Системная ошибка. Свяжитесь с менеджером',
                    "errors" =>  [
                        [
                            'Системная ошибка. Свяжитесь с менеджером'
                        ]
                    ]
                ],
                403
            );
        }

        $order->update(['status'=>'accepted_by_artist']);
        $order->refresh();

        return response()->json($order,200);
    }

    public function artistCancelOrder(Request $request, $orderId) {
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

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(
                [
                    'message' => 'Заказ не найден',
                    "errors" =>  [
                        [
                            'Заказ не найден'
                        ]
                    ]
                ],
                404
            );
        }


        if (($order->artwork->artist_id != $user->artist->id) || ($order->status != "hold")) {
            // тут все возможные проверки на ошибки статусов и состояний
            return response()->json(
                [
                    'message' => 'Системная ошибка. Свяжитесь с менеджером',
                    "errors" =>  [
                        [
                            'Системная ошибка. Свяжитесь с менеджером'
                        ]
                    ]
                ],
                403
            );
        }

        $order->update(['status'=>'cancelled_by_artist']);
        $order->refresh();

        //TODO: можно либо здесь, либо в watcher заказа (наверное логичнее там) сделать возврат холда при отмене заказа

        return response()->json($order,200);
    }
}
