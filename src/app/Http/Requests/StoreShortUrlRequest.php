<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShortUrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
     public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'original_url' => ['required', 'url', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'original_url.required' => 'The URL is required.',
            'original_url.url' => 'Please provide a valid URL.',
            'original_url.max' => 'The URL is too long.',
        ];
    }
}