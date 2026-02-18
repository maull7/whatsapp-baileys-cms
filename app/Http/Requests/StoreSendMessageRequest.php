<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'number' => ['required', 'string', 'max:20'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }
}
