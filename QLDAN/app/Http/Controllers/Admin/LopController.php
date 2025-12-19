<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\Khoa;
use App\Models\Nganh;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\DB;

class LopController extends Controller
{
    public function index(Request $request)
    {
        $query = Lop::withCount('sinhviens')
            ->with(['khoa', 'nganh', 'sinhviens']);

        // Lọc theo Khoa
        if ($request->filled('khoa')) {
            $query->where('MaKhoa', $request->khoa);
        }

        $lops = $query->paginate(8)->appends($request->query());
        $khoas = Khoa::all();
        $nganhs = Nganh::all();
        return view('admin.lop.index', compact('lops', 'khoas', 'nganhs'));
    }

    public function create()
    {
        $khoas = Khoa::all();
        $nganhs = Nganh::all();
        return view('admin.lop.create', compact('khoas','nganhs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenLop' => 'required|string|max:200',
            'MaKhoa' => 'nullable|integer|exists:Khoa,MaKhoa',
            'MaNganh' => 'nullable|integer|exists:Nganh,MaNganh',
            'TenKhoaNew' => 'nullable|string|max:255',
            'TenNganhNew' => 'nullable|string|max:255'
        ]);

        $data = $request->only('TenLop', 'MaKhoa', 'MaNganh');

        // Xử lý thêm mới Khoa
        if ($request->filled('TenKhoaNew')) {
            $khoa = Khoa::firstOrCreate(['TenKhoa' => $request->TenKhoaNew]);
            $data['MaKhoa'] = $khoa->MaKhoa;
        }

        // Xử lý thêm mới Ngành
        if ($request->filled('TenNganhNew')) {
            $nganh = Nganh::firstOrCreate(['TenNganh' => $request->TenNganhNew]);
            $data['MaNganh'] = $nganh->MaNganh;
        }

        $lop = Lop::create($data);
        return response()->json(['success'=>true,'lop'=>$lop]);
    }

    public function edit($id)
    {
        $lop = Lop::findOrFail($id);
        return response()->json(['lop'=>$lop]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenLop' => 'required|string|max:200',
            'MaKhoa' => 'nullable|integer|exists:Khoa,MaKhoa',
            'MaNganh' => 'nullable|integer|exists:Nganh,MaNganh',
            'TenKhoaNew' => 'nullable|string|max:255',
            'TenNganhNew' => 'nullable|string|max:255'
        ]);

        $lop = Lop::findOrFail($id);
        $data = $request->only('TenLop', 'MaKhoa', 'MaNganh');

        // Xử lý thêm mới Khoa
        if ($request->filled('TenKhoaNew')) {
            $khoa = Khoa::firstOrCreate(['TenKhoa' => $request->TenKhoaNew]);
            $data['MaKhoa'] = $khoa->MaKhoa;
        }

        // Xử lý thêm mới Ngành
        if ($request->filled('TenNganhNew')) {
            $nganh = Nganh::firstOrCreate(['TenNganh' => $request->TenNganhNew]);
            $data['MaNganh'] = $nganh->MaNganh;
        }

        $lop->update($data);
        return response()->json(['success'=>true,'lop'=>$lop]);
    }

    public function destroy($id)
    {
        Lop::destroy($id);
        return response()->json(['success'=>true]);
    }

    public function export()
    {
        $filePath = storage_path('app/public/lop_export.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['Mã Lớp', 'Tên Lớp', 'Khoa', 'Ngành']);

        $lops = Lop::with(['khoa', 'nganh'])->get();

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

                $tenKhoa = trim($row['Khoa'] ?? '');
                $tenNganh = trim($row['Ngành'] ?? '');

                $khoa = null;
                if (!empty($tenKhoa)) {
                    $khoa = Khoa::firstOrCreate(['TenKhoa' => $tenKhoa]);
                }

                $nganh = null;
                if (!empty($tenNganh)) {
                    $nganh = Nganh::firstOrCreate(['TenNganh' => $tenNganh]);
                }

                Lop::create([
                    'TenLop' => $tenLop,
                    'MaKhoa' => $khoa?->MaKhoa,
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
}
