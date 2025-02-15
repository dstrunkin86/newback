<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreArtworkRequest extends FormRequest
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
            'title' => 'required|array',
            'description' => 'required|array',
            'year' => 'nullable|integer',
            'artist_id' => 'required|integer',
            'width' => 'nullable|integer|required_if:in_sale,1',
            'height' => 'nullable|integer|required_if:in_sale,1',
            'depth' => 'nullable|integer|required_if:in_sale,1',
            'weight' => 'nullable|integer|required_if:in_sale,1',
            'status' => 'required|string|in:new,accepted,rejected',
            'status_comment' => 'nullable|string',
            'in_sale' => 'required|boolean',
            'price' => 'nullable|integer|required_if:in_sale,1',
            'location' => 'nullable|array|required_if:in_sale,1',
            'tags' => 'sometimes|array',
            'compilations' => 'sometimes|array',
            'images' => 'sometimes|array',
        ];
    }
}
