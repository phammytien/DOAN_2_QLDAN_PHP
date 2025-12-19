<?php

// namespace App\Http\Controllers\GiangVien;

// use App\Http\Controllers\Controller;
// use App\Models\TienDo;
// use App\Models\DeTai;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class TienDoController extends Controller
// {
//     public function index()
//     {
        
//         $maGV = Auth::user()?->MaSo;
//         $tiendos = TienDo::whereHas('deTai', function($q) use ($maGV){
//             $q->where('MaGV', $maGV);
//         })->with('deTai')->get();
//         return view('giangvien.tiendo.index', compact('tiendos'));
//     }

//     public function update(Request $request, $id)
//     {
//         $t = TienDo::findOrFail($id);
//         $request->validate(['TrangThai' => 'nullable|string|max:50','GhiChu' => 'nullable|string|max:300']);
//         $t->update($request->only('TrangThai','GhiChu'));
//         return back()->with('success','Cập nhật tiến độ thành công');
//     }
// }




namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\TienDo;
use App\Models\DeTai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TienDoController extends Controller
{
    public function index()
    {
        $maGV = Auth::user()?->MaSo;
        
        // Lấy danh sách đề tài của giảng viên có sinh viên đăng ký
        $detais = DeTai::with(['giangVien', 'sinhViens.lop', 'tiendos'])
            ->where('MaGV', $maGV)
            ->whereHas('sinhViens') // Chỉ lấy đề tài đã có sinh viên đăng ký
            ->orderByDesc('MaDeTai')
            ->get();
        
        return view('giangvien.tiendo.index', compact('detais'));
    }
public function edit($id)
{
    $tiendo = TienDo::findOrFail($id);

    return view('giangvien.tiendo.edit', compact('tiendo'));
}

    public function store(Request $request)
    {
        $request->validate([
            'MaDeTai' => 'required|exists:DeTai,MaDeTai',
            'NoiDung' => 'required|string|max:500',
            'Deadline' => 'required|date',
        ]);

        TienDo::create([
            'MaDeTai' => $request->MaDeTai,
            'NoiDung' => $request->NoiDung,
            'Deadline' => $request->Deadline,
            'ThoiGianCapNhat' => now(),
            'TrangThai' => 'Chưa nộp'
        ]);

        return back()->with('success', 'Đã thêm mốc tiến độ mới');
    }

    public function destroy($id)
    {
        TienDo::destroy($id);
        return back()->with('success', 'Đã xóa mốc tiến độ');
    }

    public function update(Request $request, $id)
    {
        $t = TienDo::findOrFail($id);
        $request->validate([
            'NoiDung' => 'nullable|string|max:500',
            'Deadline' => 'nullable|date',
            'TrangThai' => 'nullable|string|max:50',
            'GhiChu' => 'nullable|string|max:300'
        ]);
        $t->update($request->only('NoiDung', 'Deadline', 'TrangThai', 'GhiChu'));
        return redirect()->route('giangvien.tiendo.index')->with('success','Cập nhật tiến độ thành công');
    }

    public function approveLate($id)
    {
        $tiendo = TienDo::findOrFail($id);
        $tiendo->update(['TrangThai' => 'Được nộp bổ sung']);
        return back()->with('success', 'Đã cho phép sinh viên nộp bổ sung.');
    }

    /**
     * Hiển thị form chấm điểm tiến độ
     */
    public function chamDiem($id)
    {
        $maGV = Auth::user()?->MaSo;
        $tiendo = TienDo::with(['deTai.sinhViens', 'fileCode'])->findOrFail($id);
        
        // Kiểm tra quyền
        if ($tiendo->deTai->MaGV !== $maGV) {
            return back()->with('error', 'Bạn không có quyền chấm điểm tiến độ này.');
        }

        // Kiểm tra đề tài đã hoàn thành chưa
        if (in_array($tiendo->deTai->TrangThai, ['Hoàn thành', 'Đã hoàn thành'])) {
            return back()->with('error', 'Đề tài đã hoàn thành. Không thể chấm điểm nữa.');
        }

        // Lấy thông tin sinh viên cần chấm (nếu có parameter sv)
        $maSV = request('sv');
        $sinhVien = null;
        $chamDiem = null;
        
        if ($maSV) {
            $sinhVien = $tiendo->deTai->sinhViens->where('MaSV', $maSV)->first();
            if ($sinhVien) {
                // Lấy điểm đã chấm (nếu có)
                $chamDiem = \App\Models\ChamDiem::where('MaDeTai', $tiendo->MaDeTai)
                    ->where('MaTienDo', $tiendo->MaTienDo)
                    ->where('MaSV', $maSV)
                    ->first();
            }
        }

        return view('giangvien.tiendo.chamdiem', compact('tiendo', 'sinhVien', 'chamDiem'));
    }

    /**
     * Lưu điểm chấm tiến độ
     */
    public function luuDiem(Request $request, $id)
    {
        $maGV = Auth::user()?->MaSo;
        $tiendo = TienDo::with('deTai.sinhViens')->findOrFail($id);
        
        // Kiểm tra quyền
        if ($tiendo->deTai->MaGV !== $maGV) {
            return back()->with('error', 'Bạn không có quyền chấm điểm tiến độ này.');
        }

        // Kiểm tra đề tài đã hoàn thành chưa
        if (in_array($tiendo->deTai->TrangThai, ['Hoàn thành', 'Đã hoàn thành'])) {
            return back()->with('error', 'Đề tài đã hoàn thành. Không thể chấm điểm nữa.');
        }

        $request->validate([
            'Diem' => 'required|numeric|min:0|max:10',
            'NhanXet' => 'nullable|string|max:1000'
        ]);

        $maSV = request('sv');
        
        if ($maSV) {
            // Chấm điểm riêng cho sinh viên
            \App\Models\ChamDiem::updateOrCreate(
                [
                    'MaDeTai' => $tiendo->MaDeTai,
                    'MaTienDo' => $tiendo->MaTienDo,
                    'MaSV' => $maSV
                ],
                [
                    'MaGV' => $maGV,
                    'Diem' => $request->Diem,
                    'NhanXet' => $request->NhanXet,
                    'NgayCham' => now()
                ]
            );
            
            $sinhVien = $tiendo->deTai->sinhViens->where('MaSV', $maSV)->first();
            $message = 'Đã chấm điểm cho sinh viên ' . ($sinhVien->HoTen ?? $sinhVien->TenSV);
        } else {
            // Chấm điểm chung (lưu vào TienDo như cũ)
            $tiendo->update([
                'Diem' => $request->Diem,
                'NhanXet' => $request->NhanXet,
                'NgayCham' => now(),
                'MaGV' => $maGV
            ]);
            
            $message = 'Đã chấm điểm tiến độ thành công.';
        }

        // Tính điểm sau trừ nếu nộp trễ
        if ($tiendo->Deadline && $tiendo->NgayNop && \Carbon\Carbon::parse($tiendo->NgayNop)->gt(\Carbon\Carbon::parse($tiendo->Deadline))) {
            $diemSauTru = $request->Diem * 0.9;
            $message .= ' Điểm sau khi trừ 10% (nộp trễ): ' . number_format($diemSauTru, 2);
        }

        return redirect()->route('giangvien.tiendo.index')
            ->with('success', $message);
    }
}