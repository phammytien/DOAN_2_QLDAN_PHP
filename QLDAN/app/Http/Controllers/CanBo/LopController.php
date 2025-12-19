<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\Khoa;
use App\Models\Nganh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\DB;

class LopController extends Controller
{
    // Lấy MaKhoa của cán bộ đang đăng nhập
    private function getCanBoKhoa()
    {
        $user = Auth::user();
        $canbo = $user->canBoQL;
        return $canbo ? $canbo->MaKhoa : null;
    }

    public function index(Request $request)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        if (!$maKhoa) {
            abort(403, 'Bạn chưa được phân công quản lý khoa nào.');
        }
        
        // Chỉ lấy lớp thuộc khoa của cán bộ
        $query = Lop::where('MaKhoa', $maKhoa)
            ->withCount('sinhviens')
            ->with(['khoa', 'nganh', 'sinhviens']);

        $lops = $query->paginate(15)->appends($request->query());
        
        // Chỉ lấy khoa và ngành thuộc khoa của cán bộ
        $khoas = Khoa::where('MaKhoa', $maKhoa)->get();
        $nganhs = Nganh::whereHas('lops', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->get();
        
        return view('canbo.lop.index', compact('lops', 'khoas', 'nganhs'));
    }

    public function store(Request $request)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        if (!$maKhoa) {
            return response()->json(['success' => false, 'message' => 'Bạn chưa được phân công quản lý khoa nào.'], 403);
        }
        
        $request->validate([
            'TenLop' => 'required|string|max:200',
            'MaNganh' => 'nullable|integer|exists:Nganh,MaNganh'
        ]);

        // Tự động gán MaKhoa của cán bộ
        $lop = Lop::create([
            'TenLop' => $request->TenLop,
            'MaKhoa' => $maKhoa,
            'MaNganh' => $request->MaNganh
        ]);
        
        return response()->json(['success'=>true,'lop'=>$lop]);
    }

    public function edit($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        // Kiểm tra lớp có thuộc khoa của cán bộ không
        $lop = Lop::where('MaLop', $id)->where('MaKhoa', $maKhoa)->firstOrFail();
        
        return response()->json(['lop'=>$lop]);
    }

    public function update(Request $request, $id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        $request->validate([
            'TenLop' => 'required|string|max:200',
            'MaNganh' => 'nullable|integer|exists:Nganh,MaNganh'
        ]);

        // Chỉ cho phép sửa lớp thuộc khoa của mình
        $lop = Lop::where('MaLop', $id)->where('MaKhoa', $maKhoa)->firstOrFail();
        
        $lop->update([
            'TenLop' => $request->TenLop,
            'MaNganh' => $request->MaNganh
        ]);
        
        return response()->json(['success'=>true,'lop'=>$lop]);
    }

    public function destroy($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        // Chỉ cho phép xóa lớp thuộc khoa của mình
        $lop = Lop::where('MaLop', $id)->where('MaKhoa', $maKhoa)->firstOrFail();
        $lop->delete();
        
        return response()->json(['success'=>true]);
    }

    public function export()
    {
        $maKhoa = $this->getCanBoKhoa();
        
        $filePath = storage_path('app/public/lop_export.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['Mã Lớp', 'Tên Lớp', 'Khoa', 'Ngành']);

        // Chỉ export lớp thuộc khoa của cán bộ
        $lops = Lop::where('MaKhoa', $maKhoa)->with(['khoa', 'nganh'])->get();

        foreach ($lops as $lop) {
            $writer->addRow([
                $lop->MaLop,
                $lop->TenLop,
                $lop->khoa->TenKhoa ?? '',
                $lop->nganh->TenNganh ?? '',
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,csv']);

        $fileModel = FileHelper::uploadFile($request->file('excel_file'), 'excel');
        $filePath = public_path('img/uploads/' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fileModel->path));

        if (!file_exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy file'], 404);
        }

        try {
            $rows = SimpleExcelReader::create($filePath)->getRows();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi đọc Excel: ' . $e->getMessage()], 500);
        }

        $count = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $tenLop = trim($row['Tên Lớp'] ?? $row['TenLop'] ?? '');

                if (!$tenLop) {
                    $lineNumber = (int)$index + 2;
                    $errors[] = "Dòng {$lineNumber}: Thiếu Tên Lớp.";
                    continue;
                }

                $nganh = Nganh::where('TenNganh', $row['Ngành'] ?? '')->first();

                // Tự động gán MaKhoa của cán bộ
                Lop::create([
                    'TenLop' => $tenLop,
                    'MaKhoa' => $maKhoa,
                    'MaNganh' => $nganh?->MaNganh,
                ]);
                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Import thành công {$count} Lớp!" . (count($errors) ? " Có " . count($errors) . " dòng lỗi." : "")
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Import thất bại: ' . $e->getMessage()], 500);
        }
    }
    public function detail($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        // Kiểm tra lớp có thuộc khoa của cán bộ không
        $lop = Lop::where('MaLop', $id)
            ->where('MaKhoa', $maKhoa)
            ->with(['khoa', 'nganh'])
            ->firstOrFail();

        // Lấy danh sách sinh viên cùng thông tin đề tài và điểm
        $sinhviens = \App\Models\SinhVien::where('MaLop', $id)
            ->with(['detai.giangVien', 'diems'])
            ->paginate(20);

        return view('canbo.lop.detail', compact('lop', 'sinhviens'));
    }

    public function exportStudentList($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        // Kiểm tra lớp có thuộc khoa của cán bộ không
        $lop = Lop::where('MaLop', $id)
            ->where('MaKhoa', $maKhoa)
            ->firstOrFail();

        $sinhviens = \App\Models\SinhVien::where('MaLop', $id)
            ->with(['detai.giangVien', 'diems'])
            ->get();

        $filePath = storage_path('app/public/danh_sach_lop_' . $lop->TenLop . '.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['MSSV', 'Họ tên', 'Giới tính', 'Ngày sinh', 'Email', 'SĐT', 'Tên đề tài', 'GVHD', 'Điểm TB']);

        foreach ($sinhviens as $sv) {
            $detai = $sv->detai->first();
            $tenDeTai = $detai ? $detai->TenDeTai : '';
            $gvhd = $detai && $detai->giangVien ? $detai->giangVien->TenGV : '';
            
            // Tính điểm trung bình (giả sử lấy điểm trung bình của các cột điểm)
            $diemTB = '';
            if ($sv->diems->count() > 0) {
                $diemTB = $sv->diems->avg('Diem');
                $diemTB = number_format($diemTB, 2);
            }

            $writer->addRow([
                $sv->MaSV,
                $sv->TenSV,
                $sv->GioiTinh,
                \Carbon\Carbon::parse($sv->NgaySinh)->format('d/m/Y'),
                $sv->Email,
                $sv->SDT,
                $tenDeTai,
                $gvhd,
                $diemTB
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
