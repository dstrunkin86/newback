<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'language' => 'required|string|in:ru,en,cn,ar',
            'title' => 'required|string',
            'text' => 'nullable|string',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'publication_date' => 'nullable|date_format:Y-m-d',
            'is_published' => 'required|boolean',
            'link' => 'string|nullable'
        ];
    }
}
