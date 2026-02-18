<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBroadcastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message_template_id' => ['required', 'exists:message_templates,id'],
            'segment_id' => ['nullable', 'exists:segments,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
            'recipients_file' => ['nullable', 'file', 'mimes:csv,txt,xls,xlsx', 'max:5120'],
        ];
    }
}
