<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah digunakan.',
            'max' => ':attribute melebihi batas maksimal (:max).',
            'min' => ':attribute minimal :min.',
            'email' => 'Format :attribute tidak valid.',
            'integer' => ':attribute harus berupa angka bulat.',
            'in' => ':attribute tidak valid.',
            'exists' => ':attribute tidak ditemukan.',
            'date' => 'Format :attribute tidak valid.',
            'after_or_equal' => ':attribute tidak boleh lebih awal dari tanggal yang ditentukan.',
            'before_or_equal' => ':attribute tidak boleh melebihi tanggal yang ditentukan.',
            'string' => ':attribute harus berupa teks.',
        ];
    }
}