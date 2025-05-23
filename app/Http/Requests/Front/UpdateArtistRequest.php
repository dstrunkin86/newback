<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateArtistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        return $user->hasRole('artist');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fio' => 'sometimes|array',
            'vk' => 'sometimes|string',
            'telegram' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'city' => 'sometimes|array',
            'tags' => 'nullable|array',
            'education' => 'sometimes|array',
            'qualification' => 'sometimes|array',
            'creative_concept' => 'sometimes|array',
            'exhibitions' => 'sometimes|array',
            'publications' => 'sometimes|array',
            'images' => 'sometimes|nullable|array',
            'artworks' => 'sometimes|array',
            'artworks.*.id' => 'required',
            'artworks.*.title' => 'sometimes',
            'artworks.*.description' => 'sometimes',
            'artworks.*.year' => 'sometimes',
            'artworks.*.location' => 'sometimes',
            'artworks.*.width' => 'sometimes',
            'artworks.*.height' => 'sometimes',
            'artworks.*.depth' => 'sometimes',
            'artworks.*.weight' => 'sometimes',
            'artworks.*.price' => 'sometimes',
            'artworks.*.tech_info' => 'sometimes',
            'artworks.*.images' => 'sometimes|array',
            'artworks.*.tags' => 'sometimes|array',
        ];
    }
}
