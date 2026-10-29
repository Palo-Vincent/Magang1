<?php

namespace App\Http\Requests;

class LoanRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'item_id' => ['required', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'loan_date' => ['required', 'date', 'after_or_equal:today'],
            'due_date' => ['required', 'date', 'after_or_equal:loan_date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'item_id' => 'Barang',
            'quantity' => 'Jumlah',
            'loan_date' => 'Tanggal pinjam',
            'due_date' => 'Batas pengembalian',
        ];
    }
}