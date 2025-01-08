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
        ];
    }
}
