<?php

namespace App\Services\Delivery;

use App\Models\Order;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Cache;

class SdekService
{
    const ACCEPTED_DELIVERY_MODE = 1;
    const ACCEPTED_TARIFF_CODES = [3, 480, 121];

    public function getDeliveryOptions($from_city, $to_city, $width, $height, $depth, $weight)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.edu.cdek.ru/v2/calculator/tarifflist',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "type": 2,
                "date": "' . Carbon::now()->addDays(1)->format(DateTime::ISO8601) . '",
                "currency": 1,
                "lang": "rus",
                "from_location": {
                    "address": "' . $from_city . '"
                },
                "to_location": {
                    "address": "' . $to_city . '"
                },
                "packages": [
                    {
                        "height": ' . $this->getDimension('height', $height) . ',
                        "length": ' . $this->getDimension('depth', $depth) . ',
                        "weight": ' . $this->getDimension('weight', $weight) . ',
                        "width": ' . $this->getDimension('width', $width) . '
                    }
                ]
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->getToken()
            ),
        ));

        $response = curl_exec($curl);

        $response = json_decode($response);

        if (isset($response->tariff_codes) && is_array($response->tariff_codes)) {
            return $this->formatDeliveryTypes(array_filter($response->tariff_codes, [$this, 'returnAcceptedDeliveryTypes']));
        } else {
            return [];
        };
    }

    public function getDeliveryCost($tariff_code, $artwork_price, $from_city, $to_city, $width, $height, $depth, $weight, $need_insurance)
    {

        $curl = curl_init();

        if ($need_insurance) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, '{
                "type": 2,
                "date": "' . Carbon::now()->addDays(1)->format(DateTime::ISO8601) . '",
                "currency": 1,
                "tariff_code": "' . $tariff_code . '",
                "from_location": {
                    "address": "' . $from_city . '"
                },
                "to_location": {
                    "address": "' . $to_city . '"
                },
                "services": [
                    {
                        "code": "INSURANCE",
                        "parameter": "' . $artwork_price . '"
                    }
                ],
                "packages": [
                    {
                        "height": ' . $this->getDimension('height', $height) . ',
                        "length": ' . $this->getDimension('depth', $depth) . ',
                        "weight": ' . $this->getDimension('weight', $weight) . ',
                        "width": ' . $this->getDimension('width', $width) . '
                    }
                ]
            }');
        } else {
            curl_setopt($curl, CURLOPT_POSTFIELDS, '{
                "type": 2,
                "date": "' . Carbon::now()->addDays(1)->format(DateTime::ISO8601) . '",
                "currency": 1,
                "tariff_code": "' . $tariff_code . '",
                "from_location": {
                    "address": "' . $from_city . '"
                },
                "to_location": {
                    "address": "' . $to_city . '"
                },
                "packages": [
                    {
                        "height": ' . $this->getDimension('height', $height) . ',
                        "length": ' . $this->getDimension('depth', $depth) . ',
                        "weight": ' . $this->getDimension('weight', $weight) . ',
                        "width": ' . $this->getDimension('width', $width) . '
                    }
                ]
            }');
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.edu.cdek.ru/v2/calculator/tariff',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->getToken()
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $response = json_decode($response);
        //dd($response);

        return $this->formatDeliveryType($response);
    }

    public function makeDeliveryRequest($sender_name, $sender_phone, $sender_email, $tariff_code, $recepient_name, $recepient_email, $recepient_phone, $from_address, $to_address, $price, $width, $height, $depth, $weight, $need_insurance)
    {

        $curl = curl_init();

        if ($need_insurance) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, '{
                "type": 2,
                "tariff_code": "' . $tariff_code . '",
                "comment": "Заказ на сайте arthall.online",
                "sender": {
                    "company": "' . $sender_name . '",
                    "name": "' . $sender_name . '",
                    "email": "' . $sender_email . '",
                    "phones": [
                        {
                            "number": "' . $sender_phone . '"
                        }
                    ]
                },
                "recipient": {
                    "company": "' . $recepient_name . '",
                    "name": "' . $recepient_name . '",
                    "email": "' . $recepient_email . '",
                    "phones": [
                        {
                            "number": "' . $recepient_phone . '"
                        }
                    ]
                },
                "from_location": {
                    "address": "' . $from_address . '"
                },
                "to_location": {
                    "address": "' . $to_address . '"
                },
                "services": [
                    {
                        "code": "INSURANCE",
                        "parameter": "' . $price . '"
                    }
                ],
                "packages": [
                    {
                        "number": "1",
                        "height": ' . $this->getDimension('height', $height) . ',
                        "length": ' . $this->getDimension('depth', $depth) . ',
                        "weight": ' . $this->getDimension('weight', $weight) . ',
                        "width": ' . $this->getDimension('width', $width) . ',
                        "comment": "Произведение искусства"
                    }
                ]
            }');
        } else {
            curl_setopt($curl, CURLOPT_POSTFIELDS, '{
                "type": 2,
                "tariff_code": "' . $tariff_code . '",
                "comment": "Заказ на сайте arthall.online",
                "sender": {
                    "company": "' . $sender_name . '",
                    "name": "' . $sender_name . '",
                    "email": "' . $sender_email . '",
                    "phones": [
                        {
                            "number": "' . $sender_phone . '"
                        }
                    ]
                },
                "recipient": {
                    "company": "' . $recepient_name . '",
                    "name": "' . $recepient_name . '",
                    "email": "' . $recepient_email . '",
                    "phones": [
                        {
                            "number": "' . $recepient_phone . '"
                        }
                    ]
                },
                "from_location": {
                    "address": "' . $from_address . '"
                },
                "to_location": {
                    "address": "' . $to_address . '"
                },
                "packages": [
                    {
                        "number": "1",
                        "height": ' . $this->getDimension('height', $height) . ',
                        "length": ' . $this->getDimension('depth', $depth) . ',
                        "weight": ' . $this->getDimension('weight', $weight) . ',
                        "width": ' . $this->getDimension('width', $width) . ',
                        "comment": "Произведение искусства"
                    }
                ]
            }');
        }


        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.edu.cdek.ru/v2/orders',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->getToken()
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        if ($response->requests[0]->state == "ACCEPTED") {
            return (object) [
                'success' => true,
                'delivery_order_id' => $response->entity->uuid
            ];
        } else {
            return (object) [
                'success' => false,
                'reason' => 'Не удалось создать заказ на доставку'
            ];
        }
    }

    public function callCourier($delivery_date, $delivery_time_from, $delivery_time_to, $delivery_order_id, $sender_name, $sender_phone, $sender_address, $courier_comment)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.edu.cdek.ru/v2/intakes',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "intake_date": "'.$delivery_date.'",
                "intake_time_from": "'.$delivery_time_from.'",
                "intake_time_to": "'.$delivery_time_to.'",
                "order_uuid": "'.$delivery_order_id.'",
                "comment": "'.$courier_comment.'",
                "name": "'.$sender_name.'",
                "number": "'.$sender_phone.'",
                "address": "'.$sender_address.'",
                "need_call": true
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->getToken()
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);

        if ($response->requests[0]->state == "ACCEPTED") {
            return (object) [
                'success' => true
            ];
        } else {
            return (object) [
                'success' => false,
                'reason' => 'Не удалось вызвать курьера'
            ];
        }
    }

    public function cancelDeliveryRequest(Order $order) {}

    private function getToken()
    {
        $sdek_token = Cache::get('sdek_token');

        if (!$sdek_token) {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => config('delivery.sdek.api_url') . '/v2/oauth/token?parameters=null',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id=' . config('delivery.sdek.account') . '&client_secret=' . config('delivery.sdek.secure_password'),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);

            $response = json_decode($response);
            //dd(config('delivery.sdek.account'));

            Cache::set('sdek_token', $response->access_token, 3000);


            return $response->access_token;
        } else {
            return $sdek_token;
        }
    }

    private function returnAcceptedDeliveryTypes($type)
    {
        return (($type->delivery_mode == self::ACCEPTED_DELIVERY_MODE) && (in_array($type->tariff_code, self::ACCEPTED_TARIFF_CODES)));
    }

    private function formatDeliveryTypes($types)
    {
        $result = [];

        foreach ($types as $type) {
            $result[] = (object) [
                'option_code' => $type->tariff_code,
                'option_name' => $type->tariff_name,
                'delivery_sum' => $type->delivery_sum * 1.2,
                'calendar_min' => $type->calendar_min,
                'calendar_max' => $type->calendar_max,
            ];
        }
        return $result;
    }

    private function formatDeliveryType($type)
    {

        $result = (object) [
            'delivery_sum' => $type->delivery_sum * 1.2,
            'insurance_sum' => isset($type->services[0]) ? $type->services[0]->total_sum : 0,
            'total_sum' => $type->total_sum,
        ];

        return $result;
    }

    private function getDimension($type, $value)
    {
        switch ($type) {
            case 'width':
                return $value + config('delivery.extra_width');
                break;
            case 'height':
                return $value + config('delivery.extra_height');
                break;
            case 'depth':
                return $value + config('delivery.extra_depth');
                break;
            case 'weight':
                return $value + config('delivery.extra_weight');
                break;
        }
    }
}
