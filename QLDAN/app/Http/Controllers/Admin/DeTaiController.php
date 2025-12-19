<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeTai;
use App\Models\GiangVien;
use App\Models\CanBoQL;
use App\Models\NamHoc;
use App\Models\Nganh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\CauHinhHeThong;
use Illuminate\Support\Facades\Auth;

class DeTaiController extends Controller
{
    /**
     * Cập nhật trạng thái đề tài dựa trên thời gian đăng ký
     * Nên gọi trước khi hiển thị danh sách hoặc qua cron job
     */
private function capNhatTrangThaiTheoThoiGian()
{
    $now = now();

    // Lấy tất cả đề tài đang mở đăng ký
    $detais = DeTai::where('TrangThai', 'Mở đăng ký')->get();

    foreach ($detais as $dt) {

        // Lấy cấu hình theo năm học của đề tài
        $config = CauHinhHeThong::where('MaNamHoc', $dt->MaNamHoc)->first();
        if (!$config) continue;

        // Nếu quá hạn → đổi sang ĐÃ DUYỆT
        if ($now->gt($config->ThoiGianDongDangKy)) {
            $dt->update(['TrangThai' => 'Đã duyệt']);
        }
    }
}


    /**
     * Hiển thị danh sách đề tài (lọc theo trạng thái)
     */
    public function index(Request $request)
{
    $this->capNhatTrangThaiTheoThoiGian();

    $trangThai = $request->get('trangthai');
    $query = DeTai::with(['giangVien', 'canBo', 'sinhViens', 'namHoc']);

    if ($trangThai) {
        $query->where('TrangThai', $trangThai);
    }

    $detais = $query->orderByDesc('MaDeTai')->paginate(10);
    $thoigian = DB::table('CauHinhHeThong')->first();
    
   
    $gvs = GiangVien::all();
    $cbs = CanBoQL::all();
    $namHocs = NamHoc::all();
    $nganhs = Nganh::all();

    return view('admin.detai.index', compact('detais', 'trangThai', 'thoigian', 'gvs', 'cbs', 'namHocs', 'nganhs'));
}





    public function create()
    {
        $gvs = GiangVien::all();
        $cbs = CanBoQL::all(); 
        $namHocs = NamHoc::all();
        $nganhs = Nganh::all();

        return view('admin.detai.create', compact('gvs', 'cbs', 'namHocs', 'nganhs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'TenDeTai' => 'required|string|min:10|max:500',
            'MoTa' => 'nullable|string',
            'LinhVuc' => 'required|string',
            'LoaiDeTai' => 'nullable|string',
            'MaNamHoc' => 'required|exists:namhoc,MaNamHoc',
            'MaGV' => 'nullable|exists:giangvien,MaGV',
            'MaCB' => 'nullable|exists:canboql,MaCB'
        ], [
            'TenDeTai.required' => 'Tên đề tài không được để trống',
            'TenDeTai.min' => 'Tên đề tài phải có ít nhất 10 ký tự',
            'TenDeTai.max' => 'Tên đề tài không được vượt quá 500 ký tự',
            'LinhVuc.required' => 'Lĩnh vực không được để trống',
            'MaNamHoc.required' => 'Năm học không được để trống'
        ]);

        // Tự động sinh MaDeTai
        $lastDeTai = DeTai::orderBy('MaDeTai', 'desc')->first();
        $nextId = $lastDeTai ? intval(substr($lastDeTai->MaDeTai, 2)) + 1 : 1;
        $data['MaDeTai'] = 'DT' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        // Kiểm tra vai trò người tạo
        $user = Auth::user();
       // $user = auth()->user();
        
        // Nếu Admin hoặc Cán bộ tạo -> tự động duyệt
        // Nếu Giảng viên tạo -> cần duyệt
        if ($user && in_array($user->VaiTro, ['Admin', 'CanBo'])) {
            $data['TrangThai'] = 'Đang thực hiện';
        } else {
            $data['TrangThai'] = 'Chưa duyệt';
        }

        DeTai::create($data);

        return redirect()->route('admin.detai.index')
            ->with('success', 'Thêm đề tài thành công!');
    }

public function edit($id)
{
    $detai = DeTai::findOrFail($id);
    $gvs = GiangVien::all();
    $cbs = CanBoQL::all();
    $namhocs = NamHoc::all();
    $nganhs = Nganh::all();

    // Nếu là AJAX request, trả về partial view
    if (request()->ajax()) {
        return view('admin.detai.edit_form', compact('detai', 'gvs', 'cbs', 'namhocs', 'nganhs'));
    }

    return view('admin.detai.edit', compact('detai', 'gvs', 'cbs', 'namhocs', 'nganhs'));
}

    

    public function update(Request $request, $id)
    {
        $detai = DeTai::findOrFail($id); 

        $request->validate([
            'TenDeTai' => 'required|string|max:300',
            'LinhVuc' => 'required|string|max:100',
            'LoaiDeTai' => 'required|string|max:50',
            'MaNamHoc' => 'required|integer|exists:NamHoc,MaNamHoc',
        ]);

        $detai->update([
            'TenDeTai' => $request->TenDeTai,
            'MoTa' => $request->MoTa,
            'LinhVuc' => $request->LinhVuc,
            'LoaiDeTai' => $request->LoaiDeTai,
            'TrangThai' => $request->TrangThai ?? $detai->TrangThai,
            'MaGV' => $request->MaGV,
            'MaCB' => $request->MaCB,
            'MaNamHoc' => $request->MaNamHoc,
        ]);

        return redirect()->route('admin.detai.index')->with('success', '📝 Cập nhật đề tài thành công!');
    }

public function approve($id)// Duyệt đề tài và tự động thiết lập thời gian đăng ký theo năm học
{
    $detai = DeTai::findOrFail($id);

    // Tự động khớp cấu hình theo năm học của đề tài
    $config = CauHinhHeThong::where('MaNamHoc', $detai->MaNamHoc)->first();

    if (!$config) {
        return back()->with('error', 'Năm học này chưa được thiết lập cấu hình thời gian!');
    }

    $detai->update(['TrangThai' => 'Mở đăng ký']);

    return back()->with('success', 'Đã mở đăng ký theo đúng năm học!');
}

/**
 * Duyệt nhiều đề tài cùng lúc và thiết lập thời gian đăng ký
 */
public function approveMultiple(Request $request)
{
    $request->validate([
        'detai_ids' => 'required|string',
        'ThoiGianMoDangKy' => 'required|date',
        'ThoiGianDongDangKy' => 'required|date|after:ThoiGianMoDangKy',
    ], [
        'ThoiGianDongDangKy.after' => 'Ngày đóng đăng ký phải sau ngày mở đăng ký!'
    ]);

    // Chuyển chuỗi ID thành mảng
    $detaiIds = explode(',', $request->detai_ids);
    
    // Lấy danh sách đề tài
    $detais = DeTai::whereIn('MaDeTai', $detaiIds)->get();
    
    if ($detais->isEmpty()) {
        return back()->with('error', 'Không tìm thấy đề tài nào!');
    }

    // Nhóm đề tài theo năm học
    $namHocGroups = $detais->groupBy('MaNamHoc');
    
    // Cập nhật hoặc tạo cấu hình cho từng năm học
    foreach ($namHocGroups as $maNamHoc => $detaisInYear) {
        CauHinhHeThong::updateOrCreate(
            ['MaNamHoc' => $maNamHoc],
            [
                'ThoiGianMoDangKy' => $request->ThoiGianMoDangKy,
                'ThoiGianDongDangKy' => $request->ThoiGianDongDangKy,
            ]
        );
    }

    // Cập nhật trạng thái tất cả đề tài
    DeTai::whereIn('MaDeTai', $detaiIds)->update(['TrangThai' => 'Mở đăng ký']);

    return back()->with('success', "✅ Đã duyệt {$detais->count()} đề tài và thiết lập thời gian đăng ký!");
}


    public function complete($id) // Đánh dấu đề tài là hoàn thành
    {
        $detai = DeTai::findOrFail($id);
        $detai->update(['TrangThai' => 'Hoàn thành']);
        return back()->with('success', '🎯 Đề tài đã hoàn thành!');
    }

    public function cancel($id)
    {
        $detai = DeTai::findOrFail($id);
        $detai->update(['TrangThai' => 'Hủy']);
        return back()->with('success', '❌ Đề tài đã bị hủy!');
    }

    public function destroy($id)
    {
        $detai = DeTai::findOrFail($id);

        // KIỂM TRA AN TOÀN: Nếu đã có sinh viên đăng ký thì KHÔNG cho xóa
        if ($detai->sinhViens()->count() > 0) {
            return back()->with('error', '⚠️ Đề tài này đang có sinh viên thực hiện! Bạn phải hủy đề tài hoặc gỡ sinh viên ra trước khi xóa.');
        }

        // Xóa các bảng liên quan trước
        // 1. Xóa Báo cáo
        \App\Models\BaoCao::where('MaDeTai', $id)->delete();
        
        // 2. Xóa Chấm điểm
        \App\Models\ChamDiem::where('MaDeTai', $id)->delete();
        
        // 3. Xóa Phân công
        \App\Models\PhanCong::where('MaDeTai', $id)->delete();
        
        // 4. Xóa Tiến độ
        \App\Models\TienDo::where('MaDeTai', $id)->delete();

        // 5. Xóa Sinh viên tham gia (Pivot table)
        $detai->sinhViens()->detach();

        // Cuối cùng xóa Đề tài
        $detai->delete();

        return redirect()->route('admin.detai.index')->with('success', '🗑️ Xóa đề tài và dữ liệu liên quan thành công!');
    }

    public function destroyMultiple(Request $request) // Xóa nhiều đề tài cùng lúc
    {
        $ids = explode(',', $request->detai_ids);
        $deletedCount = 0; 
        $skippedCount = 0; 

        foreach ($ids as $id) {
            $detai = DeTai::find($id); 
            if (!$detai) continue;

            // KIỂM TRA AN TOÀN
            if ($detai->sinhViens()->count() > 0) {
                $skippedCount++;
                continue;
            }

            // Xóa dữ liệu liên quan
            \App\Models\BaoCao::where('MaDeTai', $id)->delete();
            \App\Models\ChamDiem::where('MaDeTai', $id)->delete();
            \App\Models\PhanCong::where('MaDeTai', $id)->delete();
            \App\Models\TienDo::where('MaDeTai', $id)->delete();
            $detai->sinhViens()->detach();
            
            $detai->delete();
            $deletedCount++;
        }

        $message = "Đã xóa {$deletedCount} đề tài.";
        if ($skippedCount > 0) {
            $message .= " Bỏ qua {$skippedCount} đề tài do đang có sinh viên thực hiện.";
            return back()->with('warning', $message);
        }

        return back()->with('success', $message);
    }

    public function capNhatThoiGianDangKy(Request $request)
    {
        $request->validate([
            'ThoiGianMo' => 'required|date',
            'ThoiGianDong' => 'required|date|after:ThoiGianMo',
        ]);

        DB::table('CauHinhHeThong')->updateOrInsert(
            ['id' => 1],
            [
                'ThoiGianMoDangKy' => $request->ThoiGianMo,
                'ThoiGianDongDangKy' => $request->ThoiGianDong,
                'updated_at' => now()
            ]
        );

        return back()->with('success', '🕒 Cập nhật thời gian đăng ký thành công!');
    }

    /**
     * Export danh sách sinh viên đăng ký đề tài ra CSV
     * Nhóm sinh viên theo đề tài - 1 đề tài 1 hàng
     */
    public function exportDangKy(Request $request) 
    {
        $maLop = $request->get('lop');
        
        // Query dữ liệu
        $query = DeTai::with(['giangVien', 'sinhViens.lop']);
        
        // Nếu có filter theo lớp
        if ($maLop) {
            $query->whereHas('sinhViens', function($q) use ($maLop) {
                $q->where('MaLop', $maLop);
            });
        }
        
        $detais = $query->get();
        
        // Tạo tên file
        $filename = 'Danh_sach_dang_ky_de_tai';
        if ($maLop) {
            $lop = \App\Models\Lop::find($maLop);
            if ($lop) {
                $filename .= '_' . $lop->TenLop;
            }
        }
        $filename .= '_' . date('Y-m-d_His') . '.csv';
        
        // Tạo CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($detais, $maLop) {
            $file = fopen('php://output', 'w');
            
            // BOM cho UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Thêm dòng delimiter hint cho Excel
            fwrite($file, "sep=,\n");
            
            // Header - Thêm cột cho sinh viên 2
            fputcsv($file, [
                'STT', 
                'Mã đề tài', 
                'Tên đề tài', 
                'Giảng viên hướng dẫn', 
                'Mã SV 1', 
                'Tên SV 1', 
                'Lớp SV 1',
                'Mã SV 2', 
                'Tên SV 2', 
                'Lớp SV 2'
            ], ',');
            
            // Data
            $counter = 1;
            foreach ($detais as $detai) {
                $sinhviens = $detai->sinhViens;
                
                // Nếu có filter theo lớp, chỉ lấy sinh viên của lớp đó
                if ($maLop) {
                    $sinhviens = $sinhviens->filter(function($sv) use ($maLop) {
                        return $sv->MaLop == $maLop;
                    });
                }
                
                // Bỏ qua nếu không có sinh viên nào (sau khi filter)
                if ($sinhviens->isEmpty()) {
                    continue;
                }
                
                // Lấy tối đa 2 sinh viên
                $sv1 = $sinhviens->get(0);
                $sv2 = $sinhviens->get(1);
                
                fputcsv($file, [
                    $counter++,
                    $detai->MaDeTai,
                    $detai->TenDeTai,
                    $detai->giangVien->TenGV ?? 'Chưa gán',
                    // Sinh viên 1
                    $sv1->MaSV ?? '',
                    $sv1->HoTen ?? $sv1->TenSV ?? '',
                    $sv1->lop->TenLop ?? '',
                    // Sinh viên 2 (nếu có)
                    $sv2->MaSV ?? '',
                    $sv2->HoTen ?? $sv2->TenSV ?? '',
                    $sv2->lop->TenLop ?? '',
                ], ',');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}