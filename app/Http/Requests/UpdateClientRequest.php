<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'api_key' => ['required', 'string', 'max:255', 'unique:clients,api_key,'.$this->route('client')->id],
            'api_base_url' => ['nullable', 'string', 'max:500', 'url'],
        ];
    }
}
