<?php

namespace App\Imports;

use App\Models\Khoa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KhoaImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Khoa([
            'TenKhoa' => $row['ten_khoa'] ?? $row['tenkhoa'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'ten_khoa' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'ten_khoa.required' => 'Tên Khoa không được để trống',
        ];
    }
}
