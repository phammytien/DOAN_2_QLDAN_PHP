<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TienDoController extends Controller
{
    public function index(Request $request)
    {
        // Get all topics with their latest progress
        $allDeTai = \App\Models\DeTai::with(['tiendos' => function($q) {
            $q->orderBy('ThoiGianCapNhat', 'desc');
        }])->get();
        
        // Count topics by their latest progress status
        $dungHan = 0;
        $chuaNop = 0;
        $treHan = 0;
        
        foreach ($allDeTai as $dt) {
            $latestProgress = $dt->tiendos->first();
            
            if (!$latestProgress) {
                continue; // Skip topics without progress
            }
            
            // Use same logic as view
            if (!$latestProgress->NgayNop) {
                $chuaNop++;
            } elseif ($latestProgress->Deadline && \Carbon\Carbon::parse($latestProgress->NgayNop)->gt(\Carbon\Carbon::parse($latestProgress->Deadline))) {
                $treHan++;
            } else {
                $dungHan++;
            }
        }
        
        $stats = [
            'total' => $allDeTai->count(),
            'completed' => $dungHan,
            'in_progress' => $chuaNop,
            'overdue' => $treHan,
        ];

        // Base Query - List Topics instead of Progress
        $query = \App\Models\DeTai::with(['tiendos' => function($q) {
            $q->orderBy('ThoiGianCapNhat', 'desc');
        }]);

        // Filters
        if ($request->filled('search')) {
            $query->where('TenDeTai', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('trangthai') && $request->trangthai !== 'all') {
            // Filter by Topic Status or Latest Progress Status?
            // Let's filter by Topic Status for now as it's more stable, or we can filter by whereHas tiendos
             $query->whereHas('tiendos', function($q) use ($request) {
                $q->where('TrangThai', $request->trangthai);
             });
        }

        if ($request->filled('madetai') && $request->madetai !== 'all') {
            $query->where('MaDeTai', $request->madetai);
        }

        $detais = $query->paginate(10);
        $all_detais = \App\Models\DeTai::all(); // For dropdown

        return view('canbo.tiendo', compact('detais', 'stats', 'all_detais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaDeTai' => 'required|exists:DeTai,MaDeTai',
            'NoiDung' => 'required|string',
            'Deadline' => 'nullable|date',
            'TrangThai' => 'required|string',
        ]);

        $tiendo = new \App\Models\TienDo();
        $tiendo->MaDeTai = $request->MaDeTai;
        $tiendo->NoiDung = $request->NoiDung;
        $tiendo->Deadline = $request->Deadline;
        $tiendo->TrangThai = $request->TrangThai;
        $tiendo->ThoiGianCapNhat = now();
        $tiendo->save();

        return redirect()->back()->with('success', 'Thêm tiến độ thành công!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'NoiDung' => 'required|string',
            'Deadline' => 'nullable|date',
            'TrangThai' => 'required|string',
        ]);

        $tiendo = \App\Models\TienDo::findOrFail($id);
        $tiendo->NoiDung = $request->NoiDung;
        $tiendo->Deadline = $request->Deadline;
        $tiendo->TrangThai = $request->TrangThai;
        $tiendo->ThoiGianCapNhat = now();
        $tiendo->save();

        return redirect()->back()->with('success', 'Cập nhật tiến độ thành công!');
    }

    public function destroy($id)
    {
        $tiendo = \App\Models\TienDo::findOrFail($id);
        $tiendo->delete();

        return redirect()->back()->with('success', 'Xóa tiến độ thành công!');
    }
}
