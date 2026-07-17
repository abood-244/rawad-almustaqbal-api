<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowedKeys = config('settings.allowed_keys', []);
        $rules = [];

        foreach ($allowedKeys as $key) {
            // Apply specific validation rules based on the key type
            if ($key === 'contactEmail') {
                $rules[$key] = ['nullable', 'email', 'max:255'];
            } elseif (in_array($key, ['facebook', 'twitter', 'instagram', 'snapchat', 'tiktok'])) {
                $rules[$key] = ['nullable', 'url', 'max:255'];
            } elseif ($key === 'theme') {
                $rules[$key] = ['nullable', 'string', 'in:light,dark'];
            } elseif ($key === 'language') {
                $rules[$key] = ['nullable', 'string', 'in:ar,en'];
            } else {
                $rules[$key] = ['nullable', 'string', 'max:1000'];
            }
        }

        return $rules;
    }
}
