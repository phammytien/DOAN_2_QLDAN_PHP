@extends('layouts.sinhvien')

@section('content')
<style>
    .score-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .score-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }
    
    .score-header .icon {
        width: 40px;
        height: 40px;
        background: #4285f4;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }
    
    .score-header h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #202124;
    }
    
    .score-description {
        color: #5f6368;
        font-size: 14px;
        margin-left: 52px;
        margin-bottom: 20px;
    }
    
    .results-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .results-table table {
        margin: 0;
        border: none;
    }
    
    .results-table thead th {
        background: #f8f9fa;
        color: #5f6368;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px;
        border: none;
    }
    
    .results-table tbody td {
        padding: 16px;
        border-top: 1px solid #e8eaed;
        border-left: none;
        border-right: none;
        color: #202124;
        vertical-align: middle;
    }
    
    .results-table tbody tr:first-child td {
        border-top: none;
    }
    
    .lecturer-name {
        font-weight: 500;
        color: #202124;
    }
    
    .role-text {
        color: #5f6368;
        font-size: 14px;
    }
    
    .score-value {
        font-weight: 600;
        font-size: 16px;
        color: #202124;
    }
    
    .not-published {
        color: #d93025;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-approved {
        background: #e6f4ea;
        color: #1e8e3e;
    }
    
    .status-pending {
        background: #fef7e0;
        color: #f9ab00;
    }
    
    .summary-section {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }
    
    .summary-card {
        flex: 1;
        padding: 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .summary-card.average {
        background: #e8f0fe;
    }
    
    .summary-card.final {
        background: #e6f4ea;
    }
    
    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    
    .summary-card.average .summary-icon {
        background: #4285f4;
        color: white;
    }
    
    .summary-card.final .summary-icon {
        background: #34a853;
        color: white;
    }
    
    .summary-content h6 {
        margin: 0 0 4px 0;
        color: #5f6368;
        font-size: 13px;
        font-weight: 500;
    }
    
    .summary-content .score {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }
    
    .summary-card.average .score {
        color: #1967d2;
    }
    
    .summary-card.final .score {
        color: #1e8e3e;
    }
</style>

<div class="container mt-4">
    @foreach($detais as $dt)
        <div class="score-card">
            <div class="score-header">
                <div class="icon">
                    📊
                </div>
                <h4>Kết Quả Chấm Điểm</h4>
            </div>
            <div class="score-description">
                Hệ thống quản lý đồ án.
            </div>

            @php
                // Lấy tất cả chấm điểm của sinh viên này
                $list = $dt->chamdiems->where('MaSV', $maSV);

                // 1. Điểm TB Tiến độ: Tính cả điểm cá nhân và điểm nhóm
                $totalTienDo = 0;
                $countTienDo = 0;
                
                foreach($dt->tiendos as $tiendo) {
                    $score = null;
                    
                    // a) Tìm điểm cá nhân trong $list (đã load ở trên)
                    $personalGrade = $list->firstWhere('MaTienDo', $tiendo->MaTienDo);
                    
                    if ($personalGrade) {
                        $score = $personalGrade->Diem;
                    } 
                    // b) Nếu ko có, lấy điểm nhóm
                    elseif ($tiendo->Diem !== null) {
                        $score = $tiendo->Diem;
                    }
                    
                    if ($score !== null) {
                        $totalTienDo += $score;
                        $countTienDo++;
                    }
                }
                
                $diemTBTienDo = $countTienDo > 0 ? ($totalTienDo / $countTienDo) : null;

                // 2. Điểm Báo cáo cuối: Trung bình các điểm không có MaTienDo (GVHD + GVPB)
                $diemBaoCaoCuoiList = $list->whereNull('MaTienDo');
                $diemBC = $diemBaoCaoCuoiList->avg('Diem'); // Trung bình của tất cả GV chấm báo cáo cuối
                $diemBaoCaoCuoi = $diemBaoCaoCuoiList->first(); // Lấy 1 record để check DiemCuoi

                // 3. Tính lại Điểm cuối theo công thức: 40% Tiến độ + 60% Báo cáo cuối
                $diemTongKet = null;
                if ($diemTBTienDo !== null && $diemBC !== null) {
                    $diemTongKet = ($diemTBTienDo * 0.4) + ($diemBC * 0.6);
                }

                // Lấy trạng thái duyệt từ điểm báo cáo cuối
                // Kiểm tra tất cả các cán bộ chấm (GVHD, GVPB,...) đã duyệt hết chưa
                $isApproved = $diemBaoCaoCuoiList->isNotEmpty() && $diemBaoCaoCuoiList->every(function ($item) {
                     return $item->TrangThai === 'Đã duyệt';
                });
            @endphp

            @php
                // --- CHUẨN BỊ DANH SÁCH HIỂN THỊ (GỘP CẢ ĐIỂM TIẾN ĐỘ & BÁO CÁO CUỐI) ---
                $displayList = collect();

                // 1. Thêm tất cả điểm cá nhân (ChamDiem)
                foreach($list as $cd) {
                    $displayList->push((object)[
                        'type' => 'chamdiem',
                        'data' => $cd,
                        'date' => $cd->NgayCham,
                        'sortData' => $cd  // Giữ object gốc để sort/xử lý
                    ]);
                }

                // 2. Thêm điểm nhóm (TienDo) nếu chưa có điểm cá nhân
                foreach($dt->tiendos as $tiendo) {
                    // Check nếu đã có điểm cá nhân cho tiến độ này thì bỏ qua (đã add ở trên)
                    if ($list->contains('MaTienDo', $tiendo->MaTienDo)) {
                        continue;
                    }

                    // Nếu tiến độ có điểm nhóm
                    if ($tiendo->Diem !== null) {
                        $displayList->push((object)[
                            'type' => 'tiendo', // Đánh dấu là điểm nhóm
                            'data' => $tiendo,
                            'date' => $tiendo->NgayCham ?? $tiendo->NgayNop, // TienDo có NgayCham (update mới) hoặc lấy NgayNop
                            'sortData' => $tiendo
                        ]);
                    }
                }

                // Sắp xếp theo ngày chấm mới nhất
                $displayList = $displayList->sortByDesc('date');
            @endphp

            <div class="results-table">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>GIẢNG VIÊN</th>
                            <th>VAI TRÒ</th>
                            <th>LOẠI ĐIỂM</th>
                            <th>ĐIỂM</th>
                            <th>NHẬN XÉT</th>
                            <th>NGÀY CHẤM</th>
                            <th>TRẠNG THÁI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($displayList as $item)
                            @php
                                $obj = $item->data;
                                $isTienDo = ($item->type === 'tiendo') || ($item->type === 'chamdiem' && $obj->MaTienDo);
                                
                                if ($item->type === 'chamdiem') {
                                    $gvName = $obj->giangVien->TenGV ?? '-';
                                    $maGV = $obj->MaGV;
                                    $diem = $obj->Diem;
                                    $nhanXet = $obj->NhanXet;
                                    $ngayCham = $obj->NgayCham;
                                    $trangThai = $obj->TrangThai; // 'Đã duyệt', 'Chờ duyệt'
                                } else {
                                    // TienDo (Group Grade)
                                    $gvName = $obj->giangVien->TenGV ?? 'GVHD'; // TienDo có relation giangVien
                                    $maGV = $obj->MaGV;
                                    $diem = $obj->Diem;
                                    $nhanXet = $obj->NhanXet;
                                    $ngayCham = $obj->NgayCham; // Hoặc ThoiGianCapNhat
                                    $trangThai = 'Đã duyệt'; // Điểm nhóm mặc định coi như đã duyệt/công bố
                                }

                                $vaiTro = $vaiTroTheoDeTai[$dt->MaDeTai][$maGV] ?? '-';
                                $loaiDiem = $isTienDo ? 'Điểm tiến độ' : 'Điểm báo cáo cuối';
                                $loaiDiemClass = $isTienDo ? 'badge bg-info' : 'badge bg-warning';
                            @endphp
                            <tr>
                                <td class="lecturer-name">{{ $gvName }}</td>
                                <td class="role-text">{{ $vaiTro }}</td>
                                <td><span class="{{ $loaiDiemClass }}">{{ $loaiDiem }}</span></td>
                                <td>
                                    @if($trangThai === 'Đã duyệt')
                                        <span class="score-value">{{ number_format($diem, 2) }}</span>
                                    @else
                                        <span class="not-published">⛔ Chưa công bố</span>
                                    @endif
                                </td>
                                <td class="role-text">
                                    @if($trangThai === 'Đã duyệt')
                                        {{ $nhanXet ?? 'Chưa có nhận xét' }}
                                    @else
                                        Chưa có nhận xét
                                    @endif
                                </td>
                                <td class="role-text">
                                    {{ $ngayCham ? \Carbon\Carbon::parse($ngayCham)->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td>
                                    @if($trangThai === 'Đã duyệt')
                                        <span class="status-badge status-approved">
                                            ✅ Đã duyệt
                                        </span>
                                    @else
                                        <span class="status-badge status-pending">
                                            ⏳ Chờ duyệt
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="summary-section">
                {{-- Điểm TB Tiến độ --}}
                <div class="summary-card average">
                    <div class="summary-icon">📊</div>
                    <div class="summary-content">
                        <h6>Điểm TB Tiến độ (40%)</h6>
                        <div class="score">
                            {{ $diemTBTienDo ? number_format($diemTBTienDo, 2) : 'Chưa có điểm' }}
                        </div>
                    </div>
                </div>
                
                {{-- Điểm Báo cáo cuối --}}
                <div class="summary-card" style="background: #fef7e0;">
                    <div class="summary-icon" style="background: #f9ab00; color: white;">📝</div>
                    <div class="summary-content">
                        <h6>Điểm Báo cáo cuối (60%)</h6>
                        <div class="score" style="color: #e37400;">
                            {{ ($isApproved && $diemBC !== null) ? number_format($diemBC, 2) : 'Chưa có điểm' }}
                        </div>
                    </div>
                </div>
                
                {{-- Điểm cuối (tổng hợp) --}}
                <div class="summary-card final">
                    <div class="summary-icon">🏆</div>
                    <div class="summary-content">
                        <h6>Điểm cuối (chính thức)</h6>
                        <div class="score">
                            @if($isApproved && $diemTongKet !== null)
                                {{ number_format($diemTongKet, 2) }}
                            @else
                                Chưa có điểm
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
