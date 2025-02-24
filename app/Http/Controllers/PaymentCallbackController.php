<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    /**
     * @param  Request  $request
     * @return string
     */
    public function yookassa(Request $request)
    {
        $data = $request->all();

        $order = Order::query()
            ->where('id', $data['object']['metadata']['order_id'])
            ->first();

        if ($order){
            $order->update([
                'status' => 'hold'
            ]);

            $order->createDeliveryRequest();
        }


        return "SUCCESS";
    }
}
