<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>

    <thead>
        <tr>
            <th style="font-weight: bold; text-align: center; background-color: #E3F2FD;">STT</th>
            <th style="font-weight: bold; text-align: center; background-color: #E3F2FD;">MSSV</th>
            <th style="font-weight: bold; text-align: center; background-color: #E3F2FD;">Họ tên</th>
            <th style="font-weight: bold; text-align: center; background-color: #E3F2FD;">Đề tài</th>
            <th style="font-weight: bold; text-align: center; background-color: #E3F2FD;">GVHD</th>
            <th style="font-weight: bold; text-align: center; background-color: #E3F2FD;">Điểm TB</th>
            <th style="font-weight: bold; text-align: center; background-color: #E3F2FD;">Xếp loại</th>
            <th style="font-weight: bold; text-align: center; background-color: #E3F2FD;">Ghi chú</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sinhviens as $index => $sv)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td style="text-align: center;">{{ $sv->MaSV }}</td>
            <td>{{ $sv->TenSV }}</td>
            <td>{{ $sv->deTai ? $sv->deTai->TenDeTai : 'Chưa có đề tài' }}</td>
            <td>{{ $sv->deTai && $sv->deTai->giangVien ? $sv->deTai->giangVien->TenGV : '-' }}</td>
            <td style="text-align: center;">{{ $sv->diemTrungBinh ? number_format($sv->diemTrungBinh, 1) : '-' }}</td>
            <td style="text-align: center;">
                @if($sv->diemTrungBinh)
                    @if($sv->diemTrungBinh >= 8.5) Xuất sắc
                    @elseif($sv->diemTrungBinh >= 8.0) Giỏi
                    @elseif($sv->diemTrungBinh >= 6.5) Khá
                    @elseif($sv->diemTrungBinh >= 5.0) Trung bình
                    @else Yếu
                    @endif
                @else
                    Chưa chấm
                @endif
            </td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>
