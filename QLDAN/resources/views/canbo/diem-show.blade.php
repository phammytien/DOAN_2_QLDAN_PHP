@extends('layouts.canbo')
@section('title','Chi Tiết Chấm Điểm')

@section('content')
<style>
:root {
    --primary-blue: #4285f4;
    --light-blue: #e8f0fe;
    --medium-blue: #d2e3fc;
    --yellow-bg: #fef7e0;
    --success-green: #e6f4ea;
    --text-dark: #202124;
    --text-secondary: #5f6368;
    --border-light: #dadce0;
}

.detail-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 24px;
}

.detail-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.detail-header h2 {
    font-size: 24px;
    font-weight: 500;
    color: var(--text-dark);
    margin: 0;
}

.detail-header p {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 4px 0 0 0;
}

.btn-back {
    background: white;
    border: 1px solid var(--border-light);
    border-radius: 4px;
    padding: 10px 20px;
    font-size: 15px;
    color: var(--text-dark);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-back:hover {
    background: #f8f9fa;
    color: var(--text-dark);
}

.info-section {
    background: white;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
}

.info-row {
    display: grid;
    grid-template-columns: 120px 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-label {
    font-size: 14px;
    font-weight: 500;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.info-value {
    font-size: 15px;
    color: var(--text-dark);
}

.scores-section {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
}

.scores-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-light);
}

.scores-header h6 {
    font-size: 16px;
    font-weight: 500;
    color: var(--text-dark);
    margin: 0;
}

.scores-table {
    width: 100%;
    border-collapse: collapse;
}

.scores-table thead th {
    background: #f8f9fa;
    padding: 14px 20px;
    text-align: left;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border-bottom: 1px solid var(--border-light);
}

.scores-table tbody td {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--text-dark);
    border-bottom: 1px solid #f0f0f0;
}

.scores-table tbody tr:last-child td {
    border-bottom: none;
}

.score-value {
    color: var(--primary-blue);
    font-weight: 600;
    font-size: 16px;
}

.summary-section {
    background: white;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
}

.summary-section h6 {
    font-size: 16px;
    font-weight: 500;
    color: var(--text-dark);
    margin: 0 0 20px 0;
}

.summary-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.summary-card {
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}

.summary-card.light-blue {
    background: var(--light-blue);
}

.summary-card.medium-blue {
    background: var(--medium-blue);
}

.summary-card.yellow {
    background: var(--yellow-bg);
}

.summary-card .label {
    font-size: 12px;
    color: var(--text-secondary);
    margin-bottom: 8px;
}

.summary-card .value {
    font-size: 28px;
    font-weight: 400;
    color: var(--primary-blue);
}

.summary-card.yellow .value {
    color: #f9ab00;
}

.status-alert {
    background: var(--success-green);
    border: 1px solid #34a853;
    border-radius: 8px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    color: #1e8e3e;
}

.status-alert .icon {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #34a853;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}
</style>

<div class="detail-container">
    <!-- Header -->
    <div class="detail-header">
        <div>
            <h2>Chi Tiết Chấm Điểm</h2>
            <p>Xem tại chi tiết điểm và nhận xét cho đề tài.</p>
        </div>
        <a href="{{ route('canbo.diem') }}" class="btn-back">
            ← Quay lại
        </a>
    </div>

    <!-- Project and Student Info -->
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Đề tài</div>
            <div class="info-value">{{ $cd->detai->TenDeTai }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Sinh viên</div>
            <div class="info-value">{{ $cd->sinhvien->TenSV }}</div>
        </div>
    </div>

    <!-- Scores from Lecturers -->
    <div class="scores-section">
        <div class="scores-header">
            <h6>Chi tiết điểm từ giảng viên</h6>
        </div>
        <table class="scores-table">
            <thead>
                <tr>
                    <th width="20%">VAI TRÒ</th>
                    <th width="25%">GIẢNG VIÊN</th>
                    <th width="15%">LOẠI ĐIỂM</th>
                    <th width="10%">ĐIỂM</th>
                    <th>NHẬN XÉT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listGV as $gv)
                <tr>
                    <td>{{ $gv->VaiTroDisplay ?? 'N/A' }}</td>
                    <td>{{ $gv->giangvien->TenGV }}</td>
                    <td>
                        @if($gv->MaTienDo)
                            <span class="badge bg-info text-white">Tiến độ</span>
                        @else
                            <span class="badge bg-success text-white">Báo cáo cuối</span>
                        @endif
                    </td>
                    <td><span class="score-value">{{ number_format($gv->Diem, 2) }}</span></td>
                    <td>{{ $gv->NhanXet ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Summary -->
    <div class="summary-section">
        <h6>Tổng kết điểm</h6>
        <div class="summary-cards">
            <div class="summary-card light-blue">
                <div class="label">Điểm tiến độ TB (40%)</div>
                <div class="value">{{ number_format($diemTienDoTB, 2) }}</div>
            </div>
            <div class="summary-card medium-blue">
                <div class="label">Điểm báo cáo TB (60%)</div>
                <div class="value">{{ number_format($diemBaoCaoTB, 2) }}</div>
            </div>
            <div class="summary-card yellow">
                <div class="label">Điểm cuối</div>
                <div class="value">{{ number_format($diemCuoi, 2) }}</div>
                <small style="font-size: 10px; color: var(--text-secondary); display: block; margin-top: 4px;">
                    @if($diemTienDoTB == 0)
                        ({{ number_format($diemBaoCaoTB, 2) }} × 100%)
                    @else
                        ({{ number_format($diemTienDoTB, 2) }} × 40%) + ({{ number_format($diemBaoCaoTB, 2) }} × 60%)
                    @endif
                </small>
            </div>
        </div>
    </div>

    <!-- Status Alert -->
    @if($cd->TrangThai === 'Đã duyệt')
    <div class="status-alert">
        <div class="icon">✓</div>
        <span>Điểm đã được duyệt thành công.</span>
    </div>
    @else
    <div style="background: white; border-radius: 8px; padding: 16px; box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);">
        <form action="{{ route('canbo.diem.updateStatus', $cd->MaCham) }}" method="POST">
            @csrf
            <input type="hidden" name="TrangThai" value="Đã duyệt">
            <button type="submit" class="btn btn-primary" onclick="return confirm('Xác nhận duyệt điểm này?')">
                ✓ Duyệt điểm
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
