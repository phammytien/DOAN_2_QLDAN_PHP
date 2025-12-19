<?php

namespace App\Exports;

use App\Models\DeTai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DangKyDeTaiExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $maLop;
    
    public function __construct($maLop = null)
    {
        $this->maLop = $maLop;
    }
    
    public function collection()
    {
        $query = DeTai::with(['giangVien', 'sinhViens.lop']);
        
        // Nếu có filter theo lớp
        if ($this->maLop) {
            $query->whereHas('sinhViens', function($q) {
                $q->where('MaLop', $this->maLop);
            });
        }
        
        $detais = $query->get();
        
        $data = [];
        $counter = 1;
        
        foreach ($detais as $detai) {
            foreach ($detai->sinhViens as $sinhvien) {
                // Nếu có filter theo lớp, chỉ lấy sinh viên của lớp đó
                if ($this->maLop && $sinhvien->MaLop != $this->maLop) {
                    continue;
                }
                
                $data[] = [
                    'STT' => $counter++,
                    'MaDeTai' => $detai->MaDeTai,
                    'TenDeTai' => $detai->TenDeTai,
                    'GiangVien' => $detai->giangVien->TenGV ?? 'Chưa gán',
                    'TenSinhVien' => $sinhvien->HoTen ?? $sinhvien->TenSV ?? 'Chưa có tên',
                    'Lop' => $sinhvien->lop->TenLop ?? 'N/A',
                ];
            }
        }
        
        return collect($data);
    }
    
    public function headings(): array
    {
        return ['STT', 'Mã đề tài', 'Tên đề tài', 'Giảng viên hướng dẫn', 'Tên sinh viên', 'Lớp'];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
