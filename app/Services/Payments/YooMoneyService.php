<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class YooMoneyService
{
    public function getWidgetPaymentCode($order_id, $amount, $currency, $description, $customer_email, $customer_phone, )
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.yookassa.ru/v3/payments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                    "amount": {
                    "value": "'.$amount.'",
                    "currency": "'.$currency.'"
                    },
                    "capture": false,
                    "save_payment_method": "false",
                    "confirmation": {
                    "type": "embedded"
                    },
                    "description": "'.$description.'",
                    "metadata": {
                    "order_id": "'.$order_id.'"
                    },
                    "receipt": {
                    "customer": {
                        "email": "'.$customer_email.'",
                        "phone": "'.$customer_phone.'"
                    },
                    "items": [
                        {
                        "description": "'.$description.'",
                        "quantity": 1.000,
                        "amount": {
                            "value": "'.$amount.'",
                            "currency": "'.$currency.'"
                        },
                        "vat_code": 1,
                        "payment_mode": "full_prepayment",
                        "payment_subject": "service"
                        }
                    ]
                    }
                }',
            CURLOPT_HTTPHEADER => array(
                'Idempotence-Key: '.microtime(false),
                'Content-Type: application/json',
                'Authorization: Basic '. base64_encode(config('payments.sdek_credentials'))
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        if ($response->status == "pending") {
            return (object) [
                'success' => true,
                'payment_order_id' => $response->id,
                'confirmation_token' => $response->confirmation->confirmation_token
            ];
        } else {
            return (object) [
                'success' => false,
                'reason' => 'Не удалось создать платеж в платежной системе'
            ];
        }
    }

    public function callbackHandler($request) {}

    public function cancelHold(Order $order) {}

    public function submitHold(Order $order) {}
}
