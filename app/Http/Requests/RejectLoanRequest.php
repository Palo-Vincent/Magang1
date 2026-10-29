<?php

namespace App\Http\Requests;

class RejectLoanRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'rejection_note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'rejection_note' => 'Alasan penolakan',
        ];
    }
}