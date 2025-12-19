<?php
namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\Nganh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\DB;

class NganhController extends Controller
{
    // Lấy MaKhoa của cán bộ đang đăng nhập
    private function getCanBoKhoa()
    {
        $user = Auth::user();
        $canbo = $user->canBoQL;
        return $canbo ? $canbo->MaKhoa : null;
    }

    public function index()
    {
        $maKhoa = $this->getCanBoKhoa();
        
        if (!$maKhoa) {
            abort(403, 'Bạn chưa được phân công quản lý khoa nào.');
        }
        
        // Chỉ lấy ngành có lớp thuộc khoa của cán bộ
        $nganhs = Nganh::withCount('lops')
            ->with(['lops' => function($query) use ($maKhoa) {
                $query->where('MaKhoa', $maKhoa)->withCount('sinhviens');
            }])
            ->whereHas('lops', function($query) use ($maKhoa) {
                $query->where('MaKhoa', $maKhoa);
            })
            ->get();
        
        return view('canbo.nganh.index', compact('nganhs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenNganh' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $nganh = Nganh::create($request->only('TenNganh'));
        return response()->json(['success' => true, 'nganh' => $nganh]);
    }

    public function edit($id)
    {
        $nganh = Nganh::findOrFail($id);
        return response()->json(['nganh' => $nganh]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'TenNganh' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $nganh = Nganh::findOrFail($id);
        $nganh->update($request->only('TenNganh'));
        return response()->json(['success' => true, 'nganh' => $nganh]);
    }

    public function destroy($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        // Kiểm tra xem ngành có lớp thuộc khoa của cán bộ không
        $nganh = Nganh::whereHas('lops', function($query) use ($maKhoa) {
            $query->where('MaKhoa', $maKhoa);
        })->findOrFail($id);
        
        // Chỉ xóa nếu ngành chỉ có lớp của khoa này
        $hasOtherKhoa = $nganh->lops()->where('MaKhoa', '!=', $maKhoa)->exists();
        
        if ($hasOtherKhoa) {
            return response()->json(['success' => false, 'message' => 'Không thể xóa ngành có lớp thuộc khoa khác'], 403);
        }
        
        Nganh::destroy($id);
        return response()->json(['success' => true]);
    }

    public function export()
    {
        $maKhoa = $this->getCanBoKhoa();
        
        $filePath = storage_path('app/public/nganh_export.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['Mã Ngành', 'Tên Ngành']);

        // Chỉ export ngành có lớp thuộc khoa của cán bộ
        $nganhs = Nganh::whereHas('lops', function($query) use ($maKhoa) {
            $query->where('MaKhoa', $maKhoa);
        })->get();

        foreach ($nganhs as $nganh) {
            $writer->addRow([
                $nganh->MaNganh,
                $nganh->TenNganh,
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
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
                $tenNganh = trim($row['Tên Ngành'] ?? $row['TenNganh'] ?? '');

                if (!$tenNganh) {
                    $lineNumber = (int)$index + 2;
                    $errors[] = "Dòng {$lineNumber}: Thiếu Tên Ngành.";
                    continue;
                }

                Nganh::create(['TenNganh' => $tenNganh]);
                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Import thành công {$count} Ngành!" . (count($errors) ? " Có " . count($errors) . " dòng lỗi." : "")
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Import thất bại: ' . $e->getMessage()], 500);
        }
    }
    
    public function detail($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        if (!$maKhoa) {
            return response()->json(['error' => 'Không có quyền truy cập'], 403);
        }
        
        // Lấy các lớp thuộc ngành này và thuộc khoa của cán bộ
        $lops = \App\Models\Lop::where('MaNganh', $id)
            ->where('MaKhoa', $maKhoa)
            ->with(['sinhviens' => function($query) {
                $query->select('MaSV', 'TenSV', 'GioiTinh', 'Email', 'SDT', 'MaLop');
            }])
            ->get();
        
        return response()->json(['lops' => $lops]);
    }
}
