<?php

namespace App\Http\Requests;

use App\Models\GameMatch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MatchFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public browse/search — no auth required
    }

    public function rules(): array
    {
        return [
            'sport' => ['nullable', 'string', 'exists:sports,slug'],
            'league_id' => ['nullable', 'integer', 'exists:leagues,id'],
            'status' => ['nullable', Rule::in([
                GameMatch::STATUS_SCHEDULED,
                GameMatch::STATUS_LIVE,
                GameMatch::STATUS_FINISHED,
                GameMatch::STATUS_POSTPONED,
                GameMatch::STATUS_CANCELLED,
            ])],
            'date' => ['nullable', 'date'],
        ];
    }
}
