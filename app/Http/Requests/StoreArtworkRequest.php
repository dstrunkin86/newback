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
            'year' => 'required|integer',
            'location' => 'required|string',
            'artist_id' => 'required|integer',
            'width' => 'required|integer',
            'height' => 'required|integer',
            'depth' => 'required|integer',
            'weight' => 'required|integer',
            'status' => 'required|string|in:new,accepted,rejected',
            'in_sale' => 'required|boolean',
            'tags' => 'sometimes|array',
            'compilations' => 'sometimes|array',
            'images' => 'sometimes|array',
        ];
    }
}
