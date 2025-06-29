<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomShortUrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'original_url' => ['required', 'url', 'max:2048'],
            'custom_alias' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z0-9_-]+$/',
                'unique:short_urls,custom_alias'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'original_url.required' => 'The URL is required.',
            'original_url.url' => 'Please provide a valid URL.',
            'original_url.max' => 'The URL is too long.',
            'custom_alias.required' => 'Custom alias is required.',
            'custom_alias.min' => 'Custom alias must be at least 3 characters.',
            'custom_alias.max' => 'Custom alias cannot exceed 50 characters.',
            'custom_alias.regex' => 'Custom alias can only contain letters, numbers, hyphens, and underscores.',
            'custom_alias.unique' => 'This custom alias is already taken.',
        ];
    }
}