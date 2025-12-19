<?php

namespace App\Exports;

use App\Models\Nganh;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NganhExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Nganh::all();
    }

    public function headings(): array
    {
        return [
            'Mã Ngành',
            'Tên Ngành',
        ];
    }

    public function map($nganh): array
    {
        return [
            $nganh->MaNganh,
            $nganh->TenNganh,
        ];
    }
}
