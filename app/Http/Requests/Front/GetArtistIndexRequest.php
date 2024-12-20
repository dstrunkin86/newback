<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class GetArtistIndexRequest extends FormRequest
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
            'source' => 'sometimes|string',
            'status_in' => 'sometimes|string',
            'fio' => 'sometimes|string',
            'having_tags' => 'sometimes|string',
            'city' => 'sometimes|string',
        ];
    }
}
