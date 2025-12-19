<?php

namespace App\Exports;

use App\Models\Khoa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KhoaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Khoa::all();
    }

    public function headings(): array
    {
        return [
            'Mã Khoa',
            'Tên Khoa',
        ];
    }

    public function map($khoa): array
    {
        return [
            $khoa->MaKhoa,
            $khoa->TenKhoa,
        ];
    }
}
