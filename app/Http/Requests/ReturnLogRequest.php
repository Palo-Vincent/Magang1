<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ReturnLogRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $loanDate = $this->route('loan')->loan_date->toDateString();

        return [
            'returned_at' => ['required', 'date', 'after_or_equal:'.$loanDate, 'before_or_equal:today'],
            'condition_on_return' => ['required', Rule::in(['baik', 'rusak ringan', 'rusak berat'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'returned_at' => 'Tanggal pengembalian',
            'condition_on_return' => 'Kondisi saat dikembalikan',
            'notes' => 'Catatan',
        ];
    }
}