<?php

namespace App\Imports;

use App\Models\Lop;
use App\Models\Khoa;
use App\Models\Nganh;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LopImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find Khoa by name
        $khoa = null;
        if (!empty($row['khoa'])) {
            $khoa = Khoa::where('TenKhoa', $row['khoa'])->first();
        }

        // Find Nganh by name
        $nganh = null;
        if (!empty($row['nganh'])) {
            $nganh = Nganh::where('TenNganh', $row['nganh'])->first();
        }

        return new Lop([
            'TenLop' => $row['ten_lop'] ?? $row['tenlop'] ?? null,
            'MaKhoa' => $khoa?->MaKhoa,
            'MaNganh' => $nganh?->MaNganh,
        ]);
    }

    public function rules(): array
    {
        return [
            'ten_lop' => 'required|string|max:200',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'ten_lop.required' => 'Tên Lớp không được để trống',
        ];
    }
}
