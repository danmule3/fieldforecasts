<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePredictionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Prediction::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'match_id' => ['required', 'integer', 'exists:matches,id'],
            'market_id' => ['required', 'integer', 'exists:markets,id'],
            'pick' => ['required', 'string', 'max:100'],
            'odds_at_publish' => ['nullable', 'numeric', 'min:1'],
            'confidence' => ['required', 'integer', 'min:0', 'max:100'],
            'analysis' => ['required', 'string'],
            'reasoning' => ['nullable', 'string'],
            'recent_form_summary' => ['nullable', 'string'],
            'head_to_head_summary' => ['nullable', 'string'],
            'injury_notes' => ['nullable', 'string'],
            'is_premium' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
