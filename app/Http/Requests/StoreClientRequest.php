<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'api_key' => ['nullable', 'string', 'max:255', 'unique:clients,api_key'],
            'api_base_url' => ['nullable', 'string', 'max:500', 'url'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('api_key')) {
            $this->merge(['api_key' => Str::random(32)]);
        }
    }
}
