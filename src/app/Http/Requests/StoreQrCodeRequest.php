<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQrCodeRequest extends FormRequest
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
            'content' => ['required', 'string', 'max:4096'],
            'type' => ['sometimes', 'string', 'in:text,url,wifi,vcard,email,phone'],
            'format' => ['sometimes', 'string', 'in:png,svg'],
            'size' => ['sometimes', 'integer', 'min:100', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Content is required.',
            'content.max' => 'Content is too long.',
            'type.in' => 'Invalid QR code type.',
            'format.in' => 'Format must be png or svg.',
            'size.min' => 'Size must be at least 100px.',
            'size.max' => 'Size cannot exceed 1000px.',
        ];
    }
}