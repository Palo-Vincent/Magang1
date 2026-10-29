<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ItemRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $id = $this->route('item')?->id;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('items', 'code')->ignore($id)],
            'stock' => ['required', 'integer', 'min:0'],
            'condition' => ['required', Rule::in(['baik', 'rusak ringan', 'rusak berat'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'Kategori',
            'name' => 'Nama barang',
            'code' => 'Kode barang',
            'stock' => 'Stok',
            'condition' => 'Kondisi',
        ];
    }
}