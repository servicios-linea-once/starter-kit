<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class TwoFactorChallengeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['nullable', 'required_without:recovery_code', 'string'],
            'recovery_code' => ['nullable', 'required_without:code', 'string'],
        ];
    }
}
