<?php

namespace App\Models;

use App\Services\Delivery\SdekService;
use App\Services\Payments\YooMoneyService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filters\Filter;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'artwork_id',
        'artwork_price',
        'delivery_price',
        'insurance_price',
        'total_price',
        'currency',
        'status',
        'recepient_address',
        'recepient_contact',
        'delivery_system',
        'delivery_option',
        'delivery_id',
        'insurance',
        'payment_system',
        'payment_id',


    ];

    protected $casts = [
        'recepient_address' => 'object',
        'recepient_contact' => 'object',

    ];

    public function scopeFilter(Builder $builder, Filter $filter): Builder
    {
        return $filter->apply($builder);
    }

    /**
     * Get the ordered artwork.
     */
    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class);
    }

    /**
     * Create delivery request.
     */
    public function createDeliveryRequest()
    {
        $artwork = $this->artwork;
        $sdek = new SdekService;
        // считаем доставку
        $deliveryCost = $sdek->getDeliveryCost($this->delivery_option,$artwork->price,$artwork->location->city,$this->recepient_address->city,$artwork->width,$artwork->height, $artwork->depth, $artwork->weight, $this->insurance);

        $this->update([
            'delivery_price' => $deliveryCost->delivery_sum,
            'insurance_price' => $deliveryCost->insurance_sum,
        ]);

        $deliveryOrder = $sdek->makeDeliveryRequest(
            $artwork->artist->fio->ru,
            $artwork->artist->phone,
            $artwork->artist->email,
            $this->delivery_option,
            $this->recepient_contact->name,
            $this->recepient_contact->email,
            $this->recepient_contact->phone,
            $artwork->location->value,
            $this->recepient_address->value,
            $artwork->price,
            $artwork->width,
            $artwork->height,
            $artwork->depth,
            $artwork->weight,
            $this->insurance
        );

        if ($deliveryOrder->success) {
            $total_price = $this->delivery_price + $this->insurance_price + $this->artwork_price;
            $this->update([
                'status' => 'delivery_created',
                'delivery_id' => $deliveryOrder->delivery_order_id,
                'total_price' => $total_price
            ]);
            return (object) [
                'success' => true,
                'order' => $this
            ];
        } else {
            return (object) [
                'success' => false,
                'reason' => $deliveryOrder->reason
            ];
        }

    }

    /**
     * Create a payment request.
     */
    public function createPayment()
    {
        $order = $this->refresh();
        $yooMoney = new YooMoneyService;
        $paymentOrder = $yooMoney->getWidgetPaymentCode(
            $order->id,
            $order->total_price,
            $order->currency,
            'Оплата заказа №'.$order->id,
            $order->recepient_contact->email,
            $order->recepient_contact->phone
        );

        if ($paymentOrder->success) {
            $this->payment_id = $paymentOrder->payment_order_id;
            $this->update([
                'status' => 'payment_created',
                'payment_system' => 'yooMoney',
                'payment_id' => $paymentOrder->payment_order_id,
            ]);
            return (object) [
                'success' => true,
                'payment_confirmation_id' => $paymentOrder->confirmation_token
            ];
        } else {
            return (object) [
                'success' => false,
                'reason' => $paymentOrder->reason
            ];
        }

    }


}
