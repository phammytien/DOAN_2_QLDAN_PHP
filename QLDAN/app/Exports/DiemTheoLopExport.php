<?php

namespace App\Exports;

use App\Models\SinhVien;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DiemTheoLopExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $lopId;
    protected $namhocId;
    
    public function __construct($lopId, $namhocId = null)
    {
        $this->lopId = $lopId;
        $this->namhocId = $namhocId;
    }
    
    public function view(): View
    {
        $query = SinhVien::where('MaLop', $this->lopId)
            ->with(['deTai.giangVien', 'baoCao.fileBaoCao'])
            ->orderBy('TenSV');
        
        if ($this->namhocId) {
            $query->whereHas('deTai', function($q) {
                $q->where('MaNamHoc', $this->namhocId);
            });
        }
        
        $sinhviens = $query->get();
        
        // Tính điểm cho mỗi sinh viên
        foreach ($sinhviens as $sv) {
            if ($sv->deTai) {
                $diem = DB::table('ChamDiem')
                    ->where('MaDeTai', $sv->deTai->MaDeTai)
                    ->orderBy('Diem', 'desc')
                    ->first();
                
                $sv->diemTrungBinh = ($diem && isset($diem->Diem)) ? $diem->Diem : null;
            } else {
                $sv->diemTrungBinh = null;
            }
        }
        
        return view('admin.baocao.export_diem', [
            'sinhviens' => $sinhviens
        ]);
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
