<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class GetArtworkIndexRequest extends FormRequest
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
            'page_size' => 'sometimes|integer',
            'sort_field' => 'sometimes|string',
            'sort_order' => 'sometimes|string|in:asc,desc',


            'status_in' => 'sometimes|string',
            'title' => 'sometimes|string',
            'having_tags' => 'sometimes|string',
            'artist_id' => 'sometimes|integer',

            'in_sale' => 'sometimes|integer|in:1,0',
            'price_from' => 'sometimes|integer',
            'price_to' => 'sometimes|integer',
            'year_from' => 'sometimes|integer',
            'year_to' => 'sometimes|integer|max:2030',
            'width_from' => 'sometimes|integer',
            'width_to' => 'sometimes|integer',
            'height_from' => 'sometimes|integer',
            'height_to' => 'sometimes|integer',
            'location' => 'sometimes|string',


        ];
    }
}
