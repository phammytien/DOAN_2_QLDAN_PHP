<?php

namespace App\Exports;

use App\Models\Lop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LopExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Lop::with(['khoa', 'nganh'])->get();
    }

    public function headings(): array
    {
        return [
            'Mã Lớp',
            'Tên Lớp',
            'Khoa',
            'Ngành',
        ];
    }

    public function map($lop): array
    {
        return [
            $lop->MaLop,
            $lop->TenLop,
            $lop->khoa->TenKhoa ?? '',
            $lop->nganh->TenNganh ?? '',
        ];
    }
}
