<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class StoreArtistRequest extends FormRequest
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
            'fio' => 'required|array',
            'vk' => 'required|string',
            'telegram' => 'required|string',
            'phone' => 'required|string',
            'city' => 'required|array',
            'tags' => 'nullable|array',
            'education' => 'nullable|array',
            'qualification' => 'nullable|array',
            'creative_concept' => 'required|array',
            'creative_concept.ru' => 'required|string',
            'exhibitions' => 'nullable|array',
            'publications' => 'nullable|array',
            'images' => 'nullable|array',
            'artworks' => 'required|array|min:5|max:10'
        ];
    }
}
