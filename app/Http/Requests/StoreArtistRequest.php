<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'source' => 'required|string',
            'status' => 'required|string',
            'fio' => 'required|array',
            // временно для совместимости со старым артхоллом  'email' => 'required|email',
            'email' => 'required|string',
            'vk' => 'required|string',
            'telegram' => 'required|string',
            'phone' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'external_id' => 'nullable|integer',
            'tags' => 'nullable|array',
            'education' => 'nullable|array',
            'qualification' => 'nullable|array',
            'exhibitions' => 'nullable|array',
            'publications' => 'nullable|array',
        ];
    }
}
