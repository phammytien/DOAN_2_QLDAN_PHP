<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\DB;

class KhoaController extends Controller
{
    public function index()
    {
        $khoas = Khoa::paginate(8);
        return view('admin.khoa.index', compact('khoas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenKhoa' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $khoa = Khoa::create($request->only('TenKhoa'));
        return response()->json(['success' => true, 'khoa' => $khoa]);
    }

    public function edit($id)
    {
        $khoa = Khoa::findOrFail($id);
        return response()->json(['khoa' => $khoa]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'TenKhoa' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $khoa = Khoa::findOrFail($id);
        $khoa->update($request->only('TenKhoa'));
        return response()->json(['success' => true, 'khoa' => $khoa]);
    }

    public function destroy($id)
    {
        Khoa::destroy($id);
        return response()->json(['success' => true]);
    }

    public function export()
    {
        $filePath = storage_path('app/public/khoa_export.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['Mã Khoa', 'Tên Khoa']);

        $khoas = Khoa::all();

        foreach ($khoas as $khoa) {
            $writer->addRow([
                $khoa->MaKhoa,
                $khoa->TenKhoa,
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
                $tenKhoa = trim($row['Tên Khoa'] ?? $row['TenKhoa'] ?? '');

                if (!$tenKhoa) {
                    $lineNumber = (int)$index + 2;
                    $errors[] = "Dòng {$lineNumber}: Thiếu Tên Khoa.";
                    continue;
                }

                Khoa::create(['TenKhoa' => $tenKhoa]);
                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Import thành công {$count} Khoa!" . (count($errors) ? " Có " . count($errors) . " dòng lỗi." : "")
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Import thất bại: ' . $e->getMessage()], 500);
        }
    }
}
