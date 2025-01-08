<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateUserRequest extends FormRequest
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
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'lang' => 'string|max:2|in:ru,en,cn,ar',
            'fcm_token' => 'string',
            'password' => 'string|min:6',
        ];
    }
}
