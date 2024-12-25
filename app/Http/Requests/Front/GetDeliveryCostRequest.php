<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class GetDeliveryCostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //recepient address block validation
            'recepient_address' => 'required|array',
            'recepient_address.fiasCode' => 'sometimes|nullable|string',
            'recepient_address.postalCode' => 'sometimes|nullable|string',
            'recepient_address.city' => 'required|string',
            'recepient_address.region' => 'required|string',
            'recepient_address.value' => 'required|string',

            //other fields
            'need_insurance' => 'required|integer|in:0,1',
            'option_code' => 'required|integer',
        ];
    }
}
