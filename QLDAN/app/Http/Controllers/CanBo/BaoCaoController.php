<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BaoCao;
use App\Models\DeTai;

class BaoCaoController extends Controller
{
    public function index(Request $request)
    {
        $query = BaoCao::with(['deTai', 'sinhVien', 'fileBaoCao', 'fileCode'])
                    ->orderBy('NgayNop', 'desc');

        if ($request->filled('search')) {
            $query->whereHas('deTai', function($q) use ($request) {
                $q->where('TenDeTai', 'like', '%' . $request->search . '%');
            })->orWhereHas('sinhVien', function($q) use ($request) {
                $q->where('TenSV', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('trangthai') && $request->trangthai !== 'all') {
            $query->where('TrangThai', $request->trangthai);
        }

        $baocaos = $query->paginate(10);

        // Optimized stats - single query instead of 3 separate queries
        $statsQuery = BaoCao::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN TrangThai = 'Chờ duyệt' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN TrangThai = 'Xin nộp bổ sung' THEN 1 ELSE 0 END) as late_request
        ")->first();

        $stats = [
            'total' => $statsQuery->total ?? 0,
            'pending' => $statsQuery->pending ?? 0,
            'late_request' => $statsQuery->late_request ?? 0,
        ];

        return view('canbo.baocao.index', compact('baocaos', 'stats'));
    }

    public function approveLate(Request $request, $id)
    {
        $baocao = BaoCao::findOrFail($id);
        
        if ($baocao->TrangThai !== 'Xin nộp bổ sung') {
            return back()->with('error', 'Trạng thái không hợp lệ.');
        }

        // Validate deadline mới
        $request->validate([
            'new_deadline' => 'required|date|after:today'
        ]);

        // Cập nhật trạng thái báo cáo
        $baocao->TrangThai = 'Được nộp bổ sung';
        $baocao->save();

        // Cập nhật deadline mới cho đề tài
        $deTai = DeTai::find($baocao->MaDeTai);
        if ($deTai) {
            $deTai->DeadlineBaoCao = $request->new_deadline;
            $deTai->save();
        }

        return back()->with('success', 'Đã duyệt yêu cầu nộp bổ sung và cập nhật deadline mới.');
    }
    
    public function rejectLate($id)
    {
        $baocao = BaoCao::findOrFail($id);
        
        if ($baocao->TrangThai !== 'Xin nộp bổ sung') {
            return back()->with('error', 'Trạng thái không hợp lệ.');
        }

        $baocao->TrangThai = 'Từ chối nộp bù';
        $baocao->save();

        return back()->with('success', 'Đã từ chối yêu cầu nộp bổ sung.');
    }
}
