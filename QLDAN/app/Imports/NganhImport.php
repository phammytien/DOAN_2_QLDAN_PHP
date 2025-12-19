<?php

namespace App\Imports;

use App\Models\Nganh;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class NganhImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Nganh([
            'TenNganh' => $row['ten_nganh'] ?? $row['tennganh'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'ten_nganh' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'ten_nganh.required' => 'Tên Ngành không được để trống',
        ];
    }
}
