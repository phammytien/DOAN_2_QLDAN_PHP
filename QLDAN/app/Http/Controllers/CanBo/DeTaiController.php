<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\DeTai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DeTaiController extends Controller
{
    public function index(Request $request)
    {
        $maCB = Auth::user()?->MaSo;
        
        // Base query - Show ALL topics, not just those created by this officer
        $query = DeTai::with(['sinhViens', 'giangVien', 'namHoc']);

        // Stats calculation
        $stats = [
            'total' => DeTai::count(),
            'pending' => DeTai::where('TrangThai', 'Chờ duyệt')->count(),
            'in_progress' => DeTai::where('TrangThai', 'Đang thực hiện')->count(),
            'completed' => DeTai::where('TrangThai', 'Đã hoàn thành')->count(),
        ];

        // Data for Filters & Create Modal
        $linhvucs = DeTai::select('LinhVuc')->distinct()->pluck('LinhVuc');
        $linhvucOptions = ['Công nghệ thông tin', 'Kinh tế', 'Ngôn ngữ Anh', 'Sư phạm', 'Nông nghiệp']; // For create modal
        $giangviens = \App\Models\GiangVien::all();
        $namhocs = \App\Models\NamHoc::all();

        // Filters
        if ($request->filled('search')) {
            $query->where('TenDeTai', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('trangthai') && $request->trangthai !== 'all') {
            $query->where('TrangThai', $request->trangthai);
        }

        if ($request->filled('linhvuc') && $request->linhvuc !== 'all') {
            $query->where('LinhVuc', $request->linhvuc);
        }

        $detais = $query->orderBy('MaDeTai', 'asc')->paginate(10);
        
        return view('canbo.detai', compact('detais', 'stats', 'linhvucs', 'linhvucOptions', 'giangviens', 'namhocs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenDeTai' => 'required|string|max:255',
            'LinhVuc' => 'required|string',
            'MoTa' => 'nullable|string',
            'YeuCau' => 'nullable|string',
            'SoLuongSV' => 'required|integer|min:1',
            'MaGV' => 'nullable|exists:GiangVien,MaGV',
            'MaNamHoc' => 'required|exists:NamHoc,MaNamHoc',
            'DeadlineBaoCao' => 'nullable|date',
        ]);

        $detai = new DeTai();
        $detai->TenDeTai = $request->TenDeTai;
        $detai->LinhVuc = $request->LinhVuc;
        $detai->MoTa = $request->MoTa;
        $detai->YeuCau = $request->YeuCau;
        $detai->SoLuongSV = $request->SoLuongSV;
        $detai->MaGV = $request->MaGV;
        $detai->MaNamHoc = $request->MaNamHoc;
        $detai->DeadlineBaoCao = $request->DeadlineBaoCao;
        $detai->TrangThai = 'Chờ duyệt';
        $detai->MaCB = Auth::user()->MaSo; // Assign to current officer
        $detai->save();

        return redirect()->route('canbo.detai.index')->with('success', 'Tạo đề tài thành công!');
    }

    public function show($id)
    {
        $detai = DeTai::with(['sinhViens', 'giangVien', 'namHoc'])
            ->findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json($detai);
        }
        
        return view('canbo.detai-show', compact('detai'));
    }

    public function approve($id)
    {
        $detai = DeTai::findOrFail($id);
        $detai->TrangThai = 'Đang thực hiện';
        $detai->save();
        return back()->with('success','Duyệt đề tài thành công');
    }

    public function reject($id)
    {
        $detai = DeTai::findOrFail($id);
        $detai->TrangThai = 'Hủy';
        $detai->save();
        return back()->with('success','Hủy đề tài thành công');
    }
}
