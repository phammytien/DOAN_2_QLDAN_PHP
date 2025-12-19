<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\TienDoController;


use App\Http\Controllers\Admin\SinhVienController;
use App\Http\Controllers\Admin\DeTaiController;
use App\Http\Controllers\Admin\ThongKeController;
use App\Http\Controllers\Admin\ThongBaoController;
use App\Http\Controllers\Admin\PhanCongController;
use App\Http\Controllers\Admin\CauHinhController;
use App\Http\Controllers\Admin\TaiKhoanController;

// Giảng viên
use App\Http\Controllers\GiangVien\DeTaiController as GVDeTaiController;
use App\Http\Controllers\GiangVien\ChamDiemController;
use App\Http\Controllers\GiangVien\HoSoController;
use App\Http\Controllers\GiangVien\ThongBaoController as GVThongBaoController;
use App\Http\Controllers\Admin\GiangVienController;
use App\Http\Controllers\Admin\BaoCaoController as AdminBaoCaoController;
use App\Http\Controllers\GiangVien\ChamDiemController as GVChamDiemController;
use App\Http\Controllers\Admin\ChamDiemController as AdminChamDiemController;



// Sinh viên
use App\Http\Controllers\SinhVien\DeTaiController as SVDeTaiController;
use App\Http\Controllers\SinhVien\BaoCaoController;
use App\Http\Controllers\SinhVien\DiemController;
use App\Http\Controllers\SinhVien\ThongBaoController as SVThongBaoController;
use App\Http\Controllers\SinhVien\ProfileController;

// ===================== TRANG WELCOME =====================
Route::get('/', fn() => view('welcome'))->name('home');

// ===================== AUTH =====================
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerView'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/request-password-reset', [AuthController::class, 'requestPasswordReset'])->name('password.request');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===================== DASHBOARD =====================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->middleware('role:Admin')->name('admin.dashboard');

    Route::get('/giangvien/dashboard', [DashboardController::class, 'giangvienDashboard'])
        ->middleware('role:GiangVien')->name('giangvien.dashboard');

    Route::get('/sinhvien/dashboard', [DashboardController::class, 'sinhvienDashboard'])
        ->middleware('role:SinhVien')->name('sinhvien.dashboard');

    Route::get('/canbo/dashboard', [DashboardController::class, 'canboDashboard'])
        ->middleware('role:CanBo')->name('canbo.dashboard');
});

// ===================== ADMIN PAGES =====================
Route::prefix('admin')->middleware(['auth', 'role:Admin'])->name('admin.')->group(function () {

    // Thêm mới hoặc cập nhật theo năm học (không có ID)
    Route::post('/cauhinh/update',
        [CauHinhController::class, 'update']
    )->name('cauhinh.update');

    // Cập nhật cấu hình theo ID
    Route::post('/cauhinh/{id}/update',
        [CauHinhController::class, 'updateTime']
    )->name('cauhinh.update.time');

    // Trang danh sách
    Route::get('/cauhinh',
        [CauHinhController::class, 'index']
    )->name('cauhinh.index');

    // Edit theo ID
    Route::get('/cauhinh/{id}/edit',
        [CauHinhController::class, 'edit']
    )->name('cauhinh.edit');

    // Delete theo ID
    Route::delete('/cauhinh/{id}',
        [CauHinhController::class, 'destroy']
    )->name('cauhinh.delete');


    // DUYỆT BÁO CÁO
    Route::post('/baocao/duyet/{id}', [AdminBaoCaoController::class, 'duyet'])->name('baocao.duyet');
    Route::post('/baocao/yeu-cau-chinh-sua/{id}', [AdminBaoCaoController::class, 'yeuCauChinhSua'])->name('baocao.yeuCauChinhSua');
    
    // DUYỆT ĐIỂM CUỐI
    Route::post('/baocao/{id}/duyet-diem', [AdminBaoCaoController::class, 'duyetDiemCuoi'])->name('baocao.duyetDiemCuoi');
    Route::post('/baocao/{id}/tu-choi-diem', [AdminBaoCaoController::class, 'tuChoiDiem'])->name('baocao.tuChoiDiem');


    // SINH VIÊN
    Route::resource('sinhvien', SinhVienController::class)->except(['show']);
    Route::get('/sinhvien/{MaSV}/detai', [SinhVienController::class, 'detai'])->name('sinhvien.detai');

  // ===================== ĐỀ TÀI (ĐÃ SỬA CHUẨN) =====================

// AJAX load form edit
Route::get('/detai/{id}/edit', [DeTaiController::class, 'edit'])
    ->name('detai.edit');

// Resource CHỈ ĐĂNG KÝ 1 LẦN
Route::resource('detai', DeTaiController::class)->except(['show']);

// Các route chức năng bổ sung
Route::post('/detai/{id}/approve', [DeTaiController::class, 'approve'])
    ->name('detai.approve');

Route::post('/detai/approve-multiple', [DeTaiController::class, 'approveMultiple'])
    ->name('detai.approve-multiple');

Route::post('/detai/delete-multiple', [DeTaiController::class, 'destroyMultiple'])
    ->name('detai.destroy_multiple');

Route::post('/detai/{id}/complete', [DeTaiController::class, 'complete'])
    ->name('detai.complete');

// Route for student registration list
Route::get('/detai/dangky', function() {
    return view('admin.detai.dangky');
})->name('detai.dangky');

// Route for exporting student registration list
Route::get('/detai/dangky/export', [\App\Http\Controllers\Admin\DeTaiController::class, 'exportDangKy'])->name('detai.dangky.export');


    // EXPORT / IMPORT
    Route::get('/sinhvien/export', [SinhVienController::class, 'export'])->name('sinhvien.export');
    Route::get('/sinhvien/export-by-class/{maLop}', [SinhVienController::class, 'exportByClass'])->name('sinhvien.export-by-class');
    Route::post('/sinhvien/import', [SinhVienController::class, 'import'])->name('sinhvien.import');

    // GIẢNG VIÊN
    Route::get('/giangvien/export', [GiangVienController::class, 'export'])->name('giangvien.export');
    Route::post('/giangvien/import', [GiangVienController::class, 'import'])->name('giangvien.import');
    Route::resource('giangvien', GiangVienController::class);

    // CÁN BỘ
    Route::get('/canbo/export', [\App\Http\Controllers\Admin\CanBoQLController::class, 'export'])->name('canbo.export');
    Route::post('/canbo/import', [\App\Http\Controllers\Admin\CanBoQLController::class, 'import'])->name('canbo.import');
    Route::get('/canbo/template', [\App\Http\Controllers\Admin\CanBoQLController::class, 'downloadTemplate'])->name('canbo.template');
    Route::resource('canbo', \App\Http\Controllers\Admin\CanBoQLController::class);
        // ----- NEW ADMIN CRUD ROUTES FOR LOP, KHOA, NGANH -----
        // LOP
        Route::get('lop', [\App\Http\Controllers\Admin\LopController::class, 'index'])->name('lop.index');
        Route::post('lop/store', [\App\Http\Controllers\Admin\LopController::class, 'store'])->name('lop.store');
        Route::get('lop/{id}/edit', [\App\Http\Controllers\Admin\LopController::class, 'edit'])->name('lop.edit');
        Route::put('lop/{id}', [\App\Http\Controllers\Admin\LopController::class, 'update'])->name('lop.update');
        Route::delete('lop/{id}', [\App\Http\Controllers\Admin\LopController::class, 'destroy'])->name('lop.destroy');
        Route::get('lop/export', [\App\Http\Controllers\Admin\LopController::class, 'export'])->name('lop.export');
        Route::post('lop/import', [\App\Http\Controllers\Admin\LopController::class, 'import'])->name('lop.import');

        // BÁO CÁO
    Route::get('/baocao/thongke', [\App\Http\Controllers\Admin\BaoCaoThongKeController::class, 'index'])->name('baocao.thongke');
    Route::get('/baocao/export-diem', [\App\Http\Controllers\Admin\BaoCaoThongKeController::class, 'exportDiem'])->name('baocao.export-diem');


        // KHOA
        Route::get('khoa', [\App\Http\Controllers\Admin\KhoaController::class, 'index'])->name('khoa.index');
        Route::post('khoa/store', [\App\Http\Controllers\Admin\KhoaController::class, 'store'])->name('khoa.store');
        Route::get('khoa/{id}/edit', [\App\Http\Controllers\Admin\KhoaController::class, 'edit'])->name('khoa.edit');
        Route::put('khoa/{id}', [\App\Http\Controllers\Admin\KhoaController::class, 'update'])->name('khoa.update');
        Route::delete('khoa/{id}', [\App\Http\Controllers\Admin\KhoaController::class, 'destroy'])->name('khoa.destroy');
        Route::get('khoa/export', [\App\Http\Controllers\Admin\KhoaController::class, 'export'])->name('khoa.export');
        Route::post('khoa/import', [\App\Http\Controllers\Admin\KhoaController::class, 'import'])->name('khoa.import');

        // NGANH
        Route::get('nganh', [\App\Http\Controllers\Admin\NganhController::class, 'index'])->name('nganh.index');
        Route::post('nganh/store', [\App\Http\Controllers\Admin\NganhController::class, 'store'])->name('nganh.store');
        Route::get('nganh/{id}/edit', [\App\Http\Controllers\Admin\NganhController::class, 'edit'])->name('nganh.edit');
        Route::get('nganh/{id}/detail', [\App\Http\Controllers\Admin\NganhController::class, 'detail'])->name('nganh.detail');
        Route::put('nganh/{id}', [\App\Http\Controllers\Admin\NganhController::class, 'update'])->name('nganh.update');
        Route::delete('nganh/{id}', [\App\Http\Controllers\Admin\NganhController::class, 'destroy'])->name('nganh.destroy');
        Route::get('nganh/export', [\App\Http\Controllers\Admin\NganhController::class, 'export'])->name('nganh.export');
        Route::post('nganh/import', [\App\Http\Controllers\Admin\NganhController::class, 'import'])->name('nganh.import');
        // ----- END NEW ADMIN CRUD ROUTES -----

    // PHÂN CÔNG
    Route::resource('phancong', PhanCongController::class);

    // THÔNG BÁO
    Route::resource('thongbao', ThongBaoController::class);

    // BÁO CÁO
    Route::resource('baocao', AdminBaoCaoController::class);
    

    // CHẤM ĐIỂM
    Route::prefix('chamdiem')->name('chamdiem.')->group(function () {
        Route::get('/', [AdminChamDiemController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminChamDiemController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminChamDiemController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminChamDiemController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminChamDiemController::class, 'destroy'])->name('destroy');
        Route::post('/update-role/{id}', [AdminChamDiemController::class, 'updateRole'])->name('updateRole');
        Route::post('/update-status/{id}', [AdminChamDiemController::class, 'updateStatus'])->name('updateStatus');
         Route::post('chamdiem/{id}/approve', [ChamDiemController::class,'approve'])
         ->name('chamdiem.approve');

         Route::post('/chamdiem/{id}/update-status', [App\Http\Controllers\Admin\ChamDiemController::class, 'updateStatus'])
     ->name('chamdiem.updateStatus');

    });
    // TÀI KHOẢN
Route::resource('taikhoan', TaiKhoanController::class);
// Route khóa / mở khóa tài khoản
    Route::patch('taikhoan/{id}/toggle-status', [TaiKhoanController::class, 'toggleStatus'])
        ->name('taikhoan.toggleStatus');
Route::patch('/taikhoan/reset/{id}', [TaiKhoanController::class, 'resetPassword'])
    ->name('taikhoan.resetPassword');
Route::get('taikhoan/ajax-search', [TaiKhoanController::class, 'ajaxSearch'])
    ->name('taikhoan.ajaxSearch');



// TIẾN ĐỘ


// TIẾN ĐỘ

// Danh sách tiến độ
Route::get('/tiendo', [TienDoController::class, 'index'])
    ->name('tiendo.index');

// Xem chi tiết tiến độ
Route::get('/tiendo/{MaTienDo}', [TienDoController::class, 'show'])
    ->name('tiendo.show');



// Đổi mật khẩu
    Route::get('/change-password', [TaiKhoanController::class, 'changePasswordView'])->name('change_password');
    Route::post('/change-password', [TaiKhoanController::class, 'changePassword'])->name('change_password.update');

});



// ===================== GIẢNG VIÊN PAGES =====================
Route::prefix('giangvien')->middleware(['auth', 'role:GiangVien'])->name('giangvien.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'giangvienDashboard'])->name('dashboard');
    Route::get('/debug-gv', function () {
        $gv = \App\Models\GiangVien::where('MaGV', \Illuminate\Support\Facades\Auth::user()->MaSo)->first();
        return response()->json([
            'MaGV' => $gv->MaGV,
            'HinhAnh' => $gv->HinhAnh,
            'AssetUrl' => asset($gv->HinhAnh),
            'PublicPath' => public_path('img/uploads/images'),
            'FileExists' => file_exists(public_path($gv->HinhAnh))
        ]);
    });

    // 👇 Quản lý giảng viên
    Route::get('/giangvien', [GiangVienController::class, 'index'])->name('giangvien.index');
   // Danh sách tiến độ
    Route::get('/tiendo', [\App\Http\Controllers\GiangVien\TienDoController::class,'index'])
        ->name('tiendo.index');

    // Form sửa tiến độ
Route::get('/tiendo/{id}/edit', [\App\Http\Controllers\GiangVien\TienDoController::class,'edit'])
    ->name('tiendo.edit');


    // Cập nhật tiến độ
    Route::put('/tiendo/{id}', [\App\Http\Controllers\GiangVien\TienDoController::class,'update'])
        ->name('tiendo.update');
    Route::post('/tiendo/store', [\App\Http\Controllers\GiangVien\TienDoController::class, 'store'])
        ->name('tiendo.store');
    Route::delete('/tiendo/{id}', [\App\Http\Controllers\GiangVien\TienDoController::class, 'destroy'])
        ->name('tiendo.destroy');
    Route::post('/tiendo/{id}/approve-late', [\App\Http\Controllers\GiangVien\TienDoController::class, 'approveLate'])
        ->name('tiendo.approveLate');

    // 👇 Quản lý đề tài
    Route::get('/detai', [GVDeTaiController::class, 'index'])->name('detai.index');
    Route::get('/detai/create', [GVDeTaiController::class, 'create'])->name('detai.create');
    Route::post('/detai/import', [GVDeTaiController::class, 'import'])->name('detai.import');
    Route::post('/detai/store', [GVDeTaiController::class, 'store'])->name('detai.store');
    Route::get('/detai/{id}/edit', [GVDeTaiController::class, 'edit'])->name('detai.edit');
    Route::put('/detai/{id}', [GVDeTaiController::class, 'update'])->name('detai.update');
    Route::delete('/detai/{id}', [GVDeTaiController::class, 'destroy'])->name('detai.destroy');
    Route::get('/detai/{id}', [GVDeTaiController::class, 'show'])->name('detai.show');

    Route::post('/hoso/change-password', [HoSoController::class, 'changePassword'])->name('hoso.changePassword');


    // 👇 Chấm điểm
    Route::get('/chamdiem', [ChamDiemController::class, 'index'])->name('chamdiem.index');
    Route::post('/chamdiem/store', [ChamDiemController::class, 'store'])->name('chamdiem.store');
    Route::put('/chamdiem/update/{id}', [ChamDiemController::class, 'update'])->name('chamdiem.update');

    // 👇 Hồ sơ
    Route::get('/hoso', [HoSoController::class, 'index'])->name('hoso.index');
    Route::get('/hoso/edit', [HoSoController::class, 'edit'])->name('hoso.edit');
    Route::get('/hoso/password', [HoSoController::class, 'password'])->name('hoso.password');
    Route::post('/hoso/update', [HoSoController::class, 'update'])->name('hoso.update');


Route::post('/detai/{id}/deadline', [App\Http\Controllers\GiangVien\DeTaiController::class, 'setDeadline']
)->name('detai.deadline');


    // 👇 Thông báo
    Route::get('/thongbao', [GVThongBaoController::class, 'index'])->name('thongbao.index');

    // 👇 Báo cáo
    Route::get('/baocao', [\App\Http\Controllers\GiangVien\BaoCaoController::class, 'index'])->name('baocao.index');
    Route::post('/baocao/{id}/approve-late', [\App\Http\Controllers\GiangVien\BaoCaoController::class, 'approveLate'])->name('baocao.approveLate');
    Route::post('/baocao/{id}/reject-late', [\App\Http\Controllers\GiangVien\BaoCaoController::class, 'rejectLate'])->name('baocao.rejectLate');
    Route::post('/baocao/{id}/approve', [\App\Http\Controllers\GiangVien\BaoCaoController::class, 'approve'])->name('baocao.approve');
    Route::post('/baocao/{id}/comment', [\App\Http\Controllers\GiangVien\BaoCaoController::class, 'comment'])->name('baocao.comment');
    Route::delete('/baocao/{id}', [\App\Http\Controllers\GiangVien\BaoCaoController::class, 'destroy'])->name('baocao.destroy');

    // 👇 Chấm điểm tiến độ
    Route::get('/tiendo/{id}/chamdiem', [\App\Http\Controllers\GiangVien\TienDoController::class, 'chamDiem'])->name('tiendo.chamDiem');
    Route::post('/tiendo/{id}/luudiem', [\App\Http\Controllers\GiangVien\TienDoController::class, 'luuDiem'])->name('tiendo.luuDiem');
    
    // 👇 Tổng hợp điểm
    Route::get('/baocao/{maDeTai}/{maSV}/tonghop', [\App\Http\Controllers\GiangVien\BaoCaoController::class, 'tongHopDiem'])->name('baocao.tongHopDiem');
    Route::post('/baocao/tinhdiem', [\App\Http\Controllers\GiangVien\BaoCaoController::class, 'tinhDiemTong'])->name('baocao.tinhDiemTong');


    // 👇 Chat
    Route::get('/chat/conversations', [\App\Http\Controllers\GiangVien\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{id}', [\App\Http\Controllers\GiangVien\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/send', [\App\Http\Controllers\GiangVien\ChatController::class, 'store'])->name('chat.send');
    Route::post('/chat/{id}/read', [\App\Http\Controllers\GiangVien\ChatController::class, 'markAsRead'])->name('chat.read');
    Route::get('/chat/unread/count', [\App\Http\Controllers\GiangVien\ChatController::class, 'unreadCount'])->name('chat.unreadCount');
    Route::delete('/chat/message/{id}', [\App\Http\Controllers\GiangVien\ChatController::class, 'deleteMessage'])->name('chat.deleteMessage');
    Route::delete('/chat/conversation/{id}', [\App\Http\Controllers\GiangVien\ChatController::class, 'deleteConversation'])->name('chat.deleteConversation');
});

// ===================== SINH VIÊN PAGES =====================
Route::prefix('sinhvien')
    ->middleware(['auth', 'role:SinhVien'])
    ->name('sinhvien.')
    ->group(function () {

    // === Dashboard ===
    Route::get('/dashboard', [DashboardController::class, 'sinhvienDashboard'])
        ->name('dashboard');

    // === Đề tài ===
    Route::get('/detai', [SVDeTaiController::class, 'index'])->name('detai.index'); // danh sách đề tài
    Route::post('/detai/dangky/{id}', [SVDeTaiController::class, 'dangKy'])->name('detai.dangky');
    Route::delete('/detai/huy/{id}', [SVDeTaiController::class, 'huyDangKy'])->name('detai.huy');

    // === Báo cáo ===
    Route::get('/baocao', [BaoCaoController::class, 'index'])->name('baocao.index');
    Route::get('/baocao/create', [BaoCaoController::class, 'create'])->name('baocao.create');
    Route::post('/baocao/store', [BaoCaoController::class, 'store'])->name('baocao.store');
    Route::post('/baocao/nop', [BaoCaoController::class, 'nopBaoCao'])->name('baocao.nop');
    Route::post('/baocao/request-late', [BaoCaoController::class, 'requestLate'])->name('baocao.requestLate');
    
    // === Tiến độ ===
    Route::get('/tiendo', [\App\Http\Controllers\SinhVien\TienDoController::class, 'index'])->name('tiendo.index');
    Route::get('/tiendo/{id}', [\App\Http\Controllers\SinhVien\TienDoController::class, 'show'])->name('tiendo.show');
    Route::put('/tiendo/{id}', [\App\Http\Controllers\SinhVien\TienDoController::class, 'update'])->name('tiendo.update');
    Route::post('/tiendo/{id}/request-late', [\App\Http\Controllers\SinhVien\TienDoController::class, 'requestLate'])->name('tiendo.requestLate');

    // === Điểm ===
    Route::get('/diem', [DiemController::class, 'index'])->name('diem.index');

    // === Hồ sơ ===
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePasswordView'])->name('profile.changePasswordView');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
    Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');

    // === Chat ===
    Route::get('/chat/conversations', [\App\Http\Controllers\SinhVien\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{id}', [\App\Http\Controllers\SinhVien\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/send', [\App\Http\Controllers\SinhVien\ChatController::class, 'store'])->name('chat.send');
    Route::post('/chat/{id}/read', [\App\Http\Controllers\SinhVien\ChatController::class, 'markAsRead'])->name('chat.read');
    Route::get('/chat/unread/count', [\App\Http\Controllers\SinhVien\ChatController::class, 'unreadCount'])->name('chat.unreadCount');
    Route::delete('/chat/message/{id}', [\App\Http\Controllers\SinhVien\ChatController::class, 'deleteMessage'])->name('chat.deleteMessage');
    Route::delete('/chat/conversation/{id}', [\App\Http\Controllers\SinhVien\ChatController::class, 'deleteConversation'])->name('chat.deleteConversation');

    // === Thông báo ===
    Route::get('/thongbao', [SVThongBaoController::class, 'index'])->name('thongbao.index');

});

// ===================== CÁN BỘ PAGES =====================
Route::prefix('canbo')->middleware(['auth', 'role:CanBo'])->name('canbo.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'canboDashboard'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\CanBo\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [\App\Http\Controllers\CanBo\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [\App\Http\Controllers\CanBo\ProfileController::class, 'changePassword'])->name('profile.change-password');
    
    // Diem routes
    Route::get('/diem', [\App\Http\Controllers\CanBo\DiemController::class, 'index'])->name('diem');
    Route::post('/diem/update-score', [\App\Http\Controllers\CanBo\DiemController::class, 'updateScore'])->name('diem.updateScore');
    Route::post('/diem/batch-approve', [\App\Http\Controllers\CanBo\DiemController::class, 'batchApprove'])->name('diem.batchApprove');
    Route::get('/diem/export-excel', [\App\Http\Controllers\CanBo\DiemController::class, 'exportExcel'])->name('diem.exportExcel');
    Route::get('/diem/export-by-class', [\App\Http\Controllers\CanBo\DiemController::class, 'exportExcelByClass'])->name('diem.exportByClass');
    Route::post('/diem/update-status/{id}', [\App\Http\Controllers\CanBo\DiemController::class, 'updateStatus'])->name('diem.updateStatus');
    Route::get('/diem/show/{id}', [\App\Http\Controllers\CanBo\DiemController::class, 'show'])->name('diem.show');
    
    Route::get('/tiendo', [\App\Http\Controllers\CanBo\TienDoController::class, 'index'])->name('tiendo');
    Route::post('/tiendo/store', [\App\Http\Controllers\CanBo\TienDoController::class, 'store'])->name('tiendo.store');
    Route::put('/tiendo/{id}', [\App\Http\Controllers\CanBo\TienDoController::class, 'update'])->name('tiendo.update');
    Route::delete('/tiendo/{id}', [\App\Http\Controllers\CanBo\TienDoController::class, 'destroy'])->name('tiendo.destroy');
    Route::get('/detai', [\App\Http\Controllers\CanBo\DeTaiController::class, 'index'])->name('detai.index');
    Route::post('/detai/store', [\App\Http\Controllers\CanBo\DeTaiController::class, 'store'])->name('detai.store');
    Route::get('/detai/{id}', [\App\Http\Controllers\CanBo\DeTaiController::class, 'show'])->name('detai.show');
    Route::post('/detai/{id}/approve', [\App\Http\Controllers\CanBo\DeTaiController::class, 'approve'])->name('detai.approve');
    Route::post('/detai/{id}/reject', [\App\Http\Controllers\CanBo\DeTaiController::class, 'reject'])->name('detai.reject');
    Route::get('/phanbien', [\App\Http\Controllers\CanBo\PhanBienController::class, 'index'])->name('phanbien');
    Route::post('/phanbien/store', [\App\Http\Controllers\CanBo\PhanBienController::class, 'store'])->name('phanbien.store');
    Route::put('/phanbien/{id}', [\App\Http\Controllers\CanBo\PhanBienController::class, 'update'])->name('phanbien.update');
    Route::delete('/phanbien/{id}', [\App\Http\Controllers\CanBo\PhanBienController::class, 'destroy'])->name('phanbien.destroy');
    Route::get('/thongke', [\App\Http\Controllers\CanBo\ThongKeController::class, 'index'])->name('thongke');
    Route::get('/thongbao', [\App\Http\Controllers\CanBo\ThongBaoController::class, 'index'])->name('thongbao.index');
    Route::post('/thongbao', [\App\Http\Controllers\CanBo\ThongBaoController::class, 'store'])->name('thongbao.store');
    Route::get('/thongbao/{id}/edit', [\App\Http\Controllers\CanBo\ThongBaoController::class, 'edit'])->name('thongbao.edit');
    Route::put('/thongbao/{id}', [\App\Http\Controllers\CanBo\ThongBaoController::class, 'update'])->name('thongbao.update');
    Route::delete('/thongbao/{id}', [\App\Http\Controllers\CanBo\ThongBaoController::class, 'destroy'])->name('thongbao.destroy');
    
    // Bao Cao Routes
    Route::get('/baocao', [\App\Http\Controllers\CanBo\BaoCaoController::class, 'index'])->name('baocao.index');
    
    // LOP Routes
    Route::get('lop', [\App\Http\Controllers\CanBo\LopController::class, 'index'])->name('lop.index');
    Route::post('lop/store', [\App\Http\Controllers\CanBo\LopController::class, 'store'])->name('lop.store');
    Route::get('lop/{id}/edit', [\App\Http\Controllers\CanBo\LopController::class, 'edit'])->name('lop.edit');
    Route::put('lop/{id}', [\App\Http\Controllers\CanBo\LopController::class, 'update'])->name('lop.update');
    Route::delete('lop/{id}', [\App\Http\Controllers\CanBo\LopController::class, 'destroy'])->name('lop.destroy');
    Route::get('lop/export', [\App\Http\Controllers\CanBo\LopController::class, 'export'])->name('lop.export');
    Route::get('lop/{id}/detail', [\App\Http\Controllers\CanBo\LopController::class, 'detail'])->name('lop.detail');
    Route::get('lop/{id}/export-students', [\App\Http\Controllers\CanBo\LopController::class, 'exportStudentList'])->name('lop.export_students');
    Route::post('lop/import', [\App\Http\Controllers\CanBo\LopController::class, 'import'])->name('lop.import');

    // NGANH Routes
    Route::get('nganh', [\App\Http\Controllers\CanBo\NganhController::class, 'index'])->name('nganh.index');
    Route::post('nganh/store', [\App\Http\Controllers\CanBo\NganhController::class, 'store'])->name('nganh.store');
    Route::get('nganh/{id}/edit', [\App\Http\Controllers\CanBo\NganhController::class, 'edit'])->name('nganh.edit');
    Route::get('nganh/{id}/detail', [\App\Http\Controllers\CanBo\NganhController::class, 'detail'])->name('nganh.detail');
    Route::put('nganh/{id}', [\App\Http\Controllers\CanBo\NganhController::class, 'update'])->name('nganh.update');
    Route::delete('nganh/{id}', [\App\Http\Controllers\CanBo\NganhController::class, 'destroy'])->name('nganh.destroy');
    Route::get('nganh/export', [\App\Http\Controllers\CanBo\NganhController::class, 'export'])->name('nganh.export');
    Route::post('nganh/import', [\App\Http\Controllers\CanBo\NganhController::class, 'import'])->name('nganh.import');

    // SINH VIEN Routes
    Route::get('sinhvien', [\App\Http\Controllers\CanBo\SinhVienController::class, 'index'])->name('sinhvien.index');
    Route::post('sinhvien/store', [\App\Http\Controllers\CanBo\SinhVienController::class, 'store'])->name('sinhvien.store');
    Route::get('sinhvien/{id}/edit', [\App\Http\Controllers\CanBo\SinhVienController::class, 'edit'])->name('sinhvien.edit');
    Route::get('sinhvien/{id}/detail', [\App\Http\Controllers\CanBo\SinhVienController::class, 'detail'])->name('sinhvien.detail');
    Route::put('sinhvien/{id}', [\App\Http\Controllers\CanBo\SinhVienController::class, 'update'])->name('sinhvien.update');
    Route::delete('sinhvien/{id}', [\App\Http\Controllers\CanBo\SinhVienController::class, 'destroy'])->name('sinhvien.destroy');
    Route::get('sinhvien/export', [\App\Http\Controllers\CanBo\SinhVienController::class, 'export'])->name('sinhvien.export');
    Route::post('sinhvien/import', [\App\Http\Controllers\CanBo\SinhVienController::class, 'import'])->name('sinhvien.import');

    // GIANG VIEN Routes
    Route::get('giangvien', [\App\Http\Controllers\CanBo\GiangVienController::class, 'index'])->name('giangvien.index');
    Route::post('giangvien/store', [\App\Http\Controllers\CanBo\GiangVienController::class, 'store'])->name('giangvien.store');
    Route::get('giangvien/{id}/edit', [\App\Http\Controllers\CanBo\GiangVienController::class, 'edit'])->name('giangvien.edit');
    Route::get('giangvien/{id}/detail', [\App\Http\Controllers\CanBo\GiangVienController::class, 'detail'])->name('giangvien.detail');
    Route::put('giangvien/{id}', [\App\Http\Controllers\CanBo\GiangVienController::class, 'update'])->name('giangvien.update');
    Route::delete('giangvien/{id}', [\App\Http\Controllers\CanBo\GiangVienController::class, 'destroy'])->name('giangvien.destroy');
    Route::get('giangvien/export', [\App\Http\Controllers\CanBo\GiangVienController::class, 'export'])->name('giangvien.export');
    Route::post('giangvien/import', [\App\Http\Controllers\CanBo\GiangVienController::class, 'import'])->name('giangvien.import');
});


use Illuminate\Support\Facades\Auth;



// Route đăng xuất
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); // chuyển về trang login sau khi logout
})->name('logout');

// TEST ROUTE - DELETE AFTER USE
Route::get('/test-db-thongbao', function() {
    try {
        // Check if column exists
        $hasColumn = \Illuminate\Support\Facades\Schema::hasColumn('ThongBao', 'MaNguoiNhan');
        
        // Count records with MaNguoiNhan
        $count = 0;
        $recent = [];
        if ($hasColumn) {
            $count = \App\Models\ThongBao::whereNotNull('MaNguoiNhan')->count();
            $recent = \App\Models\ThongBao::whereNotNull('MaNguoiNhan')->latest('TGDang')->take(5)->get();
        }
        
        return response()->json([
            'has_column_MaNguoiNhan' => $hasColumn,
            'count_records_with_MaNguoiNhan' => $count,
            'recent_notifications' => $recent
        ]);
    } catch (\Exception $e) {
        return "Lỗi: " . $e->getMessage();
    }
});