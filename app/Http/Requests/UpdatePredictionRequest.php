<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePredictionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('prediction')) ?? false;
    }

    public function rules(): array
    {
        return [
            'pick' => ['sometimes', 'required', 'string', 'max:100'],
            'confidence' => ['sometimes', 'required', 'integer', 'min:0', 'max:100'],
            'analysis' => ['sometimes', 'required', 'string'],
            'reasoning' => ['nullable', 'string'],
            'recent_form_summary' => ['nullable', 'string'],
            'head_to_head_summary' => ['nullable', 'string'],
            'injury_notes' => ['nullable', 'string'],
            'is_premium' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
