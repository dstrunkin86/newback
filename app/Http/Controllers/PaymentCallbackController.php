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

        Order::query()
            ->where('id', $data['object']['metadata']['order_id'])
            ->update([
                'status' => 'hold'
            ]);


        return "SUCCESS";
    }
}
