<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nganh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\DB;

class NganhController extends Controller
{
    public function index()
    {
        $nganhs = Nganh::withCount('lops')
            ->with(['lops' => function($query) {
                $query->withCount('sinhviens')->with('khoa');
            }])
            ->paginate(8);
        return view('admin.nganh.index', compact('nganhs'));
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
        Nganh::destroy($id);
        return response()->json(['success' => true]);
    }

    public function export()
    {
        $filePath = storage_path('app/public/nganh_export.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['Mã Ngành', 'Tên Ngành']);

        $nganhs = Nganh::all();

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
        // Lấy các lớp thuộc ngành này
        $lops = \App\Models\Lop::where('MaNganh', $id)
            ->with(['sinhviens' => function($query) {
                $query->select('MaSV', 'TenSV', 'GioiTinh', 'Email', 'SDT', 'MaLop');
            }, 'khoa'])
            ->get();
        
        return response()->json(['lops' => $lops]);
    }
}
