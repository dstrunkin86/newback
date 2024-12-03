<?php

namespace App\Http\Requests;

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
            'location' => 'required|string',
            'artist_id' => 'required|integer',
            'width' => 'nullable|integer',
            'height' => 'nullable|integer',
            'depth' => 'nullable|integer',
            'weight' => 'nullable|integer',
            'status' => 'required|string|in:new,accepted,rejected',
            'status_comment' => 'nullable|string',
            'in_sale' => 'required|boolean',
            'price' => 'nullable|integer',
            'tags' => 'sometimes|array',
            'compilations' => 'sometimes|array',
            'images' => 'sometimes|array',
        ];
    }
}
