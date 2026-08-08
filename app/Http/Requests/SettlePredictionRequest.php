<?php

namespace App\Http\Requests;

use App\Models\Prediction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettlePredictionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('settle', $this->route('prediction')) ?? false;
    }

    public function rules(): array
    {
        return [
            'outcome' => ['required', Rule::in([Prediction::STATUS_WON, Prediction::STATUS_LOST, Prediction::STATUS_CANCELLED])],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
