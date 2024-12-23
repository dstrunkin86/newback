<?php

namespace App\Services\Delivery;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Cache;

class SdekService
{
    const ACCEPTED_DELIVERY_MODE = 1;
    const ACCEPTED_TARIFF_CODES = [3, 480, 121];

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

            Cache::set('sdek_token', $response->access_token,3000);


            return $response->access_token;
        } else {
            return $sdek_token;
        }
    }

    private function returnAcceptedDeliveryTypes($type)
    {
        return (($type->delivery_mode == self::ACCEPTED_DELIVERY_MODE) && (in_array($type->tariff_code, self::ACCEPTED_TARIFF_CODES)));
    }

    private function formatDeliveryType($types)
    {
        $result = [];

        foreach( $types as $type) {
            $result[] = (object) [
                'tariff_code' => $type->tariff_code,
                'tariff_name' => $type->tariff_name,
                'delivery_sum' => $type->delivery_sum,
                'calendar_min' => $type->calendar_min,
                'calendar_max' => $type->calendar_max,
            ];
        }
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

    public function getDeliveryCosts($from_index, $to_index, $width, $height, $depth, $weight)
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
                "date": "'.Carbon::now()->addDays(1)->format(DateTime::ISO8601).'",
                "currency": 1,
                "lang": "rus",
                "from_location": {
                    "postal_code": "' . $from_index . '"
                },
                "to_location": {
                    "postal_code": "' . $to_index . '"
                },
                "packages": [
                    {
                        "height": ' . $this->getDimension('height',$height) . ',
                        "length": ' . $this->getDimension('depth',$depth) . ',
                        "weight": ' . $this->getDimension('weight',$weight) . ',
                        "width": ' . $this->getDimension('width',$width) . '
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

        if (is_array($response->tariff_codes)) {
            return $this->formatDeliveryType(array_filter($response->tariff_codes,[$this, 'returnAcceptedDeliveryTypes']));
        } else {
            return [];
        };
    }
}
