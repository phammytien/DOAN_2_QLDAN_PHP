<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BaoCao;
use App\Models\DeTai;
use Illuminate\Support\Facades\DB;

class BaoCaoController extends Controller
{
    public function index()
{
    $maSV = Auth::user()->MaSo;

    // Lấy danh sách báo cáo
    $baoCao = BaoCao::where('MaSV', $maSV)
                    ->with(['fileBaoCao', 'fileCode'])
                    ->orderBy('LanNop', 'asc')
                    ->get();

    // Lấy đề tài của sinh viên
    $maDeTai = DB::table('DeTai_SinhVien')
                    ->where('MaSV', $maSV)
                    ->value('MaDeTai');

    $deTai = \App\Models\DeTai::find($maDeTai);
    $deadline = $deTai->DeadlineBaoCao ?? null;


    $tiendos = \App\Models\TienDo::where('MaDeTai', $maDeTai)
                    ->orderBy('Deadline', 'asc')
                    ->get();

    // Check xem đã có điểm cuối chưa
    $hasGrade = \App\Models\ChamDiem::where('MaSV', $maSV)
                    ->where('MaDeTai', $maDeTai)
                    ->whereNotNull('DiemCuoi')
                    ->where('TrangThai', 'Đã duyệt')
                    ->exists();

    return view('sinhvien.baocao.index', compact('baoCao', 'deadline', 'tiendos', 'hasGrade'));
}


    /**
     * Redirect to index page (form already exists there)
     */
    public function create(Request $request)
    {
        return redirect()->route('sinhvien.baocao.index');
    }

    /**
     * Store a newly created report
     */
    public function store(Request $request)
    {
        // Redirect to nopBaoCao method which handles the actual submission
        return $this->nopBaoCao($request);
    }


    public function nopBaoCao(Request $request)
    {
        $request->validate([
            'FileBC' => 'required_without:FileCode|mimes:pdf,docx,doc|max:10240', // 10MB
            'FileCode' => 'required_without:FileBC|mimes:zip,rar,7z,tar,gz|max:20480', // 20MB
        ]);

        $sinhvien = Auth::user();

        // 🔥 Lấy mã đề tài của sinh viên
        $maDeTai = DB::table('DeTai_SinhVien')
                        ->where('MaSV', $sinhvien->MaSo)
                        ->value('MaDeTai');

        if (!$maDeTai) {
            return back()->with('error', 'Bạn chưa được phân công đề tài.');
        }

        // 🔥 Lấy đề tài để check deadline
        $deTai = DeTai::find($maDeTai);

        // Check deadline
        $deadline = $deTai->DeadlineBaoCao;
        
        // Check if student has "Được nộp bổ sung" status in previous report
        $allowedLate = false;
        $lastBC = BaoCao::where('MaSV', $sinhvien->MaSo)
                        ->where('MaDeTai', $maDeTai)
                        ->orderBy('LanNop', 'desc')
                        ->first();

        if ($lastBC && $lastBC->TrangThai === 'Được nộp bổ sung') {
            $allowedLate = true;
        }

        if ($deadline && now()->greaterThan($deadline) && !$allowedLate) {
            return back()->with('error', 'Đã quá hạn nộp báo cáo. Vui lòng xin nộp bổ sung.');
        }

        // 🔥 Upload file báo cáo (nếu có)
        $fileId = null;
        if ($request->hasFile('FileBC')) {
            $file = $request->FileBC;
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('baocao', $fileName, 'public');
            
            // Create File record
            $newFile = \App\Models\File::create([
                'name' => $fileName,
                'path' => 'storage/' . $filePath,
                'type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension(),
            ]);
            $fileId = $newFile->id;
        }

        // 🔥 Upload file code (nếu có)
        $fileCodeId = null;
        if ($request->hasFile('FileCode')) {
            $fileCode = $request->FileCode;
            $fileNameCode = time() . '_code_' . $fileCode->getClientOriginalName();
            $filePathCode = $fileCode->storeAs('baocao/code', $fileNameCode, 'public');

            // Create File record
            $newFileCode = \App\Models\File::create([
                'name' => $fileNameCode,
                'path' => 'storage/' . $filePathCode,
                'type' => $fileCode->getMimeType(),
                'size' => $fileCode->getSize(),
                'extension' => $fileCode->getClientOriginalExtension(),
            ]);
            $fileCodeId = $newFileCode->id;
        }

        // 🔥 Nếu được phép nộp bổ sung, cập nhật bản ghi cũ
        if ($allowedLate && $lastBC) {
            $updateData = [
                'NgayNop' => now(),
                'TrangThai' => 'Chờ duyệt',
            ];

            if ($fileId) {
                $updateData['FileID'] = $fileId;
            }

            if ($fileCodeId) {
                $updateData['FileCodeID'] = $fileCodeId;
            }

            $lastBC->update($updateData);
            
            return back()->with('success', 'Nộp báo cáo bổ sung thành công!');
        }

        // 🔥 Tạo bản ghi báo cáo mới (lần đầu hoặc nộp lại trong hạn)
        $lanNop = $lastBC ? ($lastBC->LanNop + 1) : 1;
        
        BaoCao::create([
            'MaDeTai'  => $maDeTai,
            'MaSV'     => $sinhvien->MaSo,
            'FileID'   => $fileId,
            'FileCodeID' => $fileCodeId,
            'NgayNop'  => now(),
            'LanNop'   => $lanNop,
            'TrangThai'=> 'Chờ duyệt',
            'Deadline' => $deadline
        ]);

        return back()->with('success', 'Nộp báo cáo thành công! (Lần nộp: ' . $lanNop . ')');
    }

    public function requestLate(Request $request)
    {
        $sinhvien = Auth::user();
        
        // Get topic
        $maDeTai = DB::table('DeTai_SinhVien')
                        ->where('MaSV', $sinhvien->MaSo)
                        ->value('MaDeTai');

        if (!$maDeTai) {
            return back()->with('error', 'Bạn chưa được phân công đề tài.');
        }

        $deTai = DeTai::find($maDeTai);

        // Check if already requested
        $lastBC = BaoCao::where('MaSV', $sinhvien->MaSo)
                        ->where('MaDeTai', $maDeTai)
                        ->orderBy('LanNop', 'desc')
                        ->first();

        if ($lastBC && ($lastBC->TrangThai === 'Xin nộp bổ sung' || $lastBC->TrangThai === 'Được nộp bổ sung' || $lastBC->TrangThai === 'Đã duyệt')) {
             return back()->with('error', 'Bạn đã gửi yêu cầu, đã được duyệt hoặc báo cáo đã hoàn thành.');
        }

        // Create a record for request
        BaoCao::create([
            'MaDeTai'  => $maDeTai,
            'MaSV'     => $sinhvien->MaSo,
            'TenFile'  => '', // No file yet
            'LinkFile' => '',
            'NgayNop'  => now(),
            'LanNop'   => $lastBC ? ($lastBC->LanNop + 1) : 1,
            'TrangThai'=> 'Xin nộp bổ sung',
            'Deadline' => $deTai->DeadlineBaoCao
        ]);

        return back()->with('success', 'Đã gửi yêu cầu xin nộp bổ sung. Vui lòng chờ cán bộ duyệt.');
    }
}