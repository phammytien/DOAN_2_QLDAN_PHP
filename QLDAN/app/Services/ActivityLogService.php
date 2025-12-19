<?php

namespace App\Services;

use App\Models\ThongBao;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Ghi log hoạt động của admin vào bảng ThongBao
     */
    public static function log($noiDung, $mucDo = 'Thông báo', $doiTuongNhan = null)
    {
        try {
            $admin = Auth::user();
            
            ThongBao::create([
                'NoiDung' => $noiDung,
                'TGDang' => now(),
                'MaCB' => $admin->MaSo ?? null,
                'DoiTuongNhan' => $doiTuongNhan,
                'MucDo' => $mucDo,
            ]);
        } catch (\Exception $e) {
            // Silent fail - không làm gián đoạn luồng chính
            logger()->error('Activity log failed: ' . $e->getMessage());

        }
    }

    /**
     * Log reset mật khẩu
     */
    public static function logPasswordReset($targetUser, $targetUserName)
    {
        $noiDung = "Mật khẩu của tài khoản đã được Admin reset lại. Mật khẩu mới: {$targetUser}";
        self::log($noiDung, 'Mật khẩu', $targetUser);
    }

    /**
     * Log gửi thông báo
     */
    public static function logNotificationSent($title, $doiTuongNhan)
    {
        $noiDung = "Giảng viên tiến hành cập nhật nội dung đề tài cho sinh viên...";
        self::log($noiDung, 'Thông báo', $doiTuongNhan);
    }

    /**
     * Log tạo user mới
     */
    public static function logUserCreated($userType, $userName, $userId)
    {
        $noiDung = "Điểm đã được công bố";
        self::log($noiDung, 'Điểm', null);
    }

    /**
     * Log cập nhật user
     */
    public static function logUserUpdated($userType, $userName, $userId)
    {
        $noiDung = "Thời gian hoàn thành đã tài vào ngày 1/10/2025 đã...";
        self::log($noiDung, 'Thời hạn', null);
    }

    /**
     * Log xóa user
     */
    public static function logUserDeleted($userType, $userName)
    {
        $noiDung = "Hoàn thành và nộp đề tài vào ngày 4/12/2025";
        self::log($noiDung, 'Nộp đề tài', null);
    }

    /**
     * Log duyệt đề tài
     */
    public static function logTopicApproved($topicName)
    {
        $noiDung = "Thời gian hoàn thành đã tài vào ngày 1/10/2025 đã...";
        self::log($noiDung, 'Thời hạn', null);
    }
}