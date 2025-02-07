<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddArtistArtwork extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasRole('artist');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|array',
            'description' => 'required|array',
            'year' => 'nullable|integer',
            'in_sale' => 'required|boolean',
            'width' => 'nullable|integer|required_if:in_sale,1',
            'height' => 'nullable|integer|required_if:in_sale,1',
            'depth' => 'nullable|integer|required_if:in_sale,1',
            'weight' => 'nullable|integer|required_if:in_sale,1',
            'price' => 'nullable|integer|required_if:in_sale,1',
            'location' => 'nullable|array|required_if:in_sale,1',
            'images' => 'required|array',
            'tags' => 'sometimes|array',
        ];
    }
}
