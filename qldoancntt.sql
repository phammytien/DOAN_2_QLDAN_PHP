-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3307
-- Thời gian đã tạo: Th12 19, 2025 lúc 04:31 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qldoancntt`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `baocao`
--

CREATE TABLE `baocao` (
  `MaBC` int(11) NOT NULL,
  `MaDeTai` int(11) DEFAULT NULL,
  `MaSV` varchar(20) DEFAULT NULL,
  `TenFile` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `LinkFile` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NgayNop` datetime DEFAULT NULL,
  `Deadline` datetime DEFAULT NULL,
  `LanNop` int(11) DEFAULT NULL,
  `NhanXet` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `TrangThai` varchar(255) NOT NULL DEFAULT 'Chưa duyệt',
  `FileID` int(11) DEFAULT NULL,
  `FileCodeID` int(11) DEFAULT NULL,
  `DiemTienDo` float DEFAULT NULL,
  `DiemBaoCaoCuoi` float DEFAULT NULL,
  `DiemTong` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `baocao`
--

INSERT INTO `baocao` (`MaBC`, `MaDeTai`, `MaSV`, `TenFile`, `LinkFile`, `NgayNop`, `Deadline`, `LanNop`, `NhanXet`, `TrangThai`, `FileID`, `FileCodeID`, `DiemTienDo`, `DiemBaoCaoCuoi`, `DiemTong`) VALUES
(3, 4, '002245004', '1762788091_CAUHOU.pdf', '/storage/baocao/1762788091_CAUHOU.pdf', '2025-11-10 15:21:31', NULL, 1, 'ok', 'Đã duyệt', NULL, NULL, NULL, NULL, NULL),
(4, 6, '0022425001', '1763137325_CHƯƠNG 8.docx', '/storage/baocao/1763137325_CHƯƠNG 8.docx', '2025-11-14 16:22:06', NULL, 1, NULL, 'Chưa duyệt', NULL, NULL, NULL, NULL, NULL),
(5, 6, '0022425001', '1763137385_PHIẾU TIẾP NHẬN SV.pdf', '/storage/baocao/1763137385_PHIẾU TIẾP NHẬN SV.pdf', '2025-11-14 16:23:05', NULL, 1, NULL, 'Đã duyệt', NULL, NULL, NULL, NULL, NULL),
(7, 4, '002245004', '1763388900_bialuanan2.pdf', '/storage/baocao/1763388900_bialuanan2.pdf', '2025-11-17 14:15:00', NULL, 1, NULL, 'Đã duyệt', NULL, NULL, NULL, NULL, NULL),
(9, 5, '002245005', '1763389665_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', '/storage/baocao/1763389665_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', '2025-11-17 14:27:45', NULL, 1, NULL, 'Chưa duyệt', NULL, NULL, NULL, NULL, NULL),
(16, 6, '0022425001', '1763393767_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'storage/baocao/1763393767_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', '2025-11-17 15:36:07', NULL, 2, NULL, 'Chờ duyệt', NULL, NULL, NULL, NULL, NULL),
(19, 7, '0022425002', '1764001116_Detaicapbo.pdf', 'storage/baocao/1764001116_Detaicapbo.pdf', '2025-11-24 16:18:38', '2025-11-25 00:00:00', 2, NULL, 'Đã duyệt', NULL, NULL, NULL, NULL, NULL),
(24, 13, '002245006', '1764244044_Tự học.docx', 'storage/baocao/1764244044_Tự học.docx', '2025-11-27 12:23:32', '2025-12-24 18:42:00', 2, 'TỐT LẮM', 'Đã duyệt', 26, 27, NULL, NULL, NULL),
(30, 40, '002245021', NULL, NULL, '2025-12-14 21:38:56', '2025-12-23 00:00:00', 1, 'đúng tiến độ', 'Đã duyệt', 79, 80, NULL, NULL, NULL),
(31, 46, '002245016', NULL, NULL, '2025-12-16 22:31:09', '2025-12-18 21:54:00', 1, 'đúng hạn', 'Đã duyệt', 91, 92, NULL, NULL, NULL),
(32, 17, '002245023', NULL, NULL, '2025-12-16 23:03:53', NULL, 1, 'đúng hẹn', 'Đã duyệt', 95, 96, NULL, NULL, NULL),
(33, 47, '002245013', NULL, NULL, '2025-12-17 11:39:15', '2025-12-13 11:33:00', 1, 'QUÁ HẠN', 'Đã duyệt', 105, 106, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `canboql`
--

CREATE TABLE `canboql` (
  `MaCB` varchar(20) NOT NULL,
  `TenCB` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `GioiTinh` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NgaySinh` datetime DEFAULT NULL,
  `MaCCCD` varchar(15) DEFAULT NULL,
  `TonGiao` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `Email` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NoiSinh` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `HKTT` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `DanToc` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `HocVi` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `HocHam` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ChuyenNganh` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `MaTK` int(11) DEFAULT NULL,
  `MaKhoa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `canboql`
--

INSERT INTO `canboql` (`MaCB`, `TenCB`, `GioiTinh`, `NgaySinh`, `MaCCCD`, `TonGiao`, `SDT`, `Email`, `NoiSinh`, `HKTT`, `DanToc`, `HocVi`, `HocHam`, `ChuyenNganh`, `MaTK`, `MaKhoa`) VALUES
('CB001', 'Nguyễn Thị Mai', 'Nữ', '1995-06-18 00:00:00', '079111222333', 'Không', '0901123444', 'mai@uni.edu.vn', 'Đồng Tháp', 'Cao Lãnh', 'Kinh', 'Thạc sĩ', 'Giáo viên', 'Công nghệ thông tin', 2, 1),
('CB002', 'Nguyễn Văn Hải', 'Nam', '1994-12-11 00:00:00', '089127456789', NULL, '0901234567', 'email@example.com', 'Đồng Tháp', 'Đồng Tháp', NULL, 'Tiến sĩ', NULL, 'Quản lý giáo dục', 38, NULL),
('CB003', 'Ngô Kiến Huy', 'Nam', '1999-05-12 00:00:00', '087456932104', 'Không', '0369852147', 'huy@gmail.com', 'Đồng Tháp', 'Cao Lãnh', 'Kinh', 'Tiến sĩ', NULL, 'Khoa học máy tính', 75, NULL),
('CB004', 'Nguyễn Văn A', 'Nam', '1978-02-19 00:00:00', '089623456789', 'Không', '0909060001', 'nguyenvana@example.com', 'Đồng Tháp', 'Đồng Tháp', 'Kinh', 'Tiến sĩ', 'Phó giáo sư', 'Công nghệ thông tin', 99, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cauhinhhethong`
--

CREATE TABLE `cauhinhhethong` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `MaNamHoc` int(11) DEFAULT NULL,
  `ThoiGianMoDangKy` datetime DEFAULT NULL,
  `ThoiGianDongDangKy` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cauhinhhethong`
--

INSERT INTO `cauhinhhethong` (`id`, `MaNamHoc`, `ThoiGianMoDangKy`, `ThoiGianDongDangKy`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-11-04 20:43:00', '2025-12-27 20:43:00', NULL, '2025-12-16 15:55:16'),
(4, 3, '2025-12-01 16:35:00', '2026-01-02 16:35:00', '2025-12-16 15:53:06', '2025-12-17 09:35:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cccd`
--

CREATE TABLE `cccd` (
  `MaCCCD` varchar(15) NOT NULL,
  `NgayCap` datetime DEFAULT NULL,
  `NoiCap` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cccd`
--

INSERT INTO `cccd` (`MaCCCD`, `NgayCap`, `NoiCap`) VALUES
('001234567890', '2025-11-28 14:28:03', 'Chưa cập nhật'),
('079111222333', '2021-01-01 00:00:00', 'Đà Nẵng'),
('079123456789', '2020-01-01 00:00:00', 'TP.HCM'),
('079444555666', '2021-03-01 00:00:00', 'Cần Thơ'),
('079456311202', '2021-02-14 00:00:00', 'Đồng Tháp'),
('079555444333', '2020-05-05 00:00:00', 'Vũng Tàu'),
('079777888999', '2019-11-01 00:00:00', 'Bình Dương'),
('079888777666', '2018-10-10 00:00:00', 'Bến Tre'),
('079987654321', '2020-02-01 00:00:00', 'Hà Nội'),
('079999888777', '2022-02-02 00:00:00', 'TP.HCM'),
('083456787013', '2025-11-28 19:47:59', 'Chưa cập nhật'),
('083456787014', '2025-11-28 19:48:00', 'Chưa cập nhật'),
('083456787015', '2025-11-28 19:48:00', 'Chưa cập nhật'),
('083456787016', '2025-11-28 19:48:01', 'Chưa cập nhật'),
('083456787017', '2025-11-28 19:48:02', 'Chưa cập nhật'),
('083456787018', '2025-11-28 19:48:02', 'Chưa cập nhật'),
('083456787019', '2025-11-28 19:48:03', 'Chưa cập nhật'),
('083456787020', '2025-11-28 19:48:03', 'Chưa cập nhật'),
('083456787021', '2025-11-28 19:48:04', 'Chưa cập nhật'),
('085123456789', '2025-12-01 00:30:30', 'Chưa cập nhật'),
('087345678702', '2025-11-28 19:47:58', 'Chưa cập nhật'),
('087446989524', NULL, NULL),
('087455989524', NULL, NULL),
('087456689524', '2021-02-14 00:00:00', 'Đồng Tháp'),
('087456789524', '2025-02-14 00:00:00', 'Đồng Tháp'),
('087456932104', NULL, NULL),
('087456932158', NULL, NULL),
('087456979524', '2025-03-18 00:00:00', 'Đồng Tháp'),
('087456989524', '2025-11-14 11:16:32', 'Chưa cập nhật'),
('087456989525', '2025-11-28 12:59:21', 'Chưa cập nhật'),
('087496989524', NULL, NULL),
('087556989524', '2020-02-12 00:00:00', 'Đồng Tháp'),
('087653982134', NULL, NULL),
('087656982134', NULL, NULL),
('087756989524', '2022-08-14 00:00:00', 'Đồng Tháp'),
('087896523147', '2025-11-29 21:13:07', 'Chưa cập nhật'),
('087896529147', '2025-11-29 21:36:45', 'Chưa cập nhật'),
('087897329187', '2025-12-16 13:18:54', 'Chưa cập nhật'),
('087897529187', '2025-12-14 19:32:15', 'Chưa cập nhật'),
('087912345679', '2025-12-17 00:00:00', 'Đồng Tháp'),
('087945612301', '2025-11-28 13:51:51', 'Chưa cập nhật'),
('087945625138', '2025-11-21 11:19:48', 'Chưa cập nhật'),
('087945632102', NULL, NULL),
('087945665138', '2025-11-28 13:56:45', 'Chưa cập nhật'),
('087954612351', '2025-11-29 21:21:03', 'Chưa cập nhật'),
('087985625138', NULL, NULL),
('088894561561', NULL, NULL),
('088894561591', '2025-11-14 10:09:36', 'Chưa cập nhật'),
('089123434789', '2025-11-28 00:00:00', 'Đồng Tháp'),
('089123455789', NULL, NULL),
('089123456739', '2023-02-01 00:00:00', 'Đồng Tháp'),
('089123456789', '2020-02-13 00:00:00', 'Đồng Tháp'),
('089123459789', '2025-12-03 20:19:40', 'Chưa cập nhật'),
('089123466789', '2024-01-30 00:00:00', 'Đồng Tháp'),
('089123856789', NULL, NULL),
('089127456789', NULL, NULL),
('089128456789', NULL, NULL),
('089129456785', '2021-02-12 00:00:00', 'Đồng Tháp'),
('089163456789', NULL, NULL),
('089183456789', NULL, NULL),
('089193456789', NULL, NULL),
('089623456789', NULL, NULL),
('089745621352', '2025-11-29 21:34:09', 'Chưa cập nhật'),
('089765431236', '2025-11-27 23:06:02', 'Chưa cập nhật'),
('12345678001', '2025-11-28 19:26:19', 'Chưa cập nhật'),
('12345678002', '2025-11-28 19:26:20', 'Chưa cập nhật'),
('12345678003', '2025-11-28 19:26:20', 'Chưa cập nhật'),
('12345678004', '2025-11-28 19:26:21', 'Chưa cập nhật'),
('12345678005', '2025-11-28 19:26:21', 'Chưa cập nhật'),
('12345678006', '2025-11-28 19:26:22', 'Chưa cập nhật'),
('12345678007', '2025-11-28 19:26:23', 'Chưa cập nhật'),
('12345678008', '2025-11-28 19:26:24', 'Chưa cập nhật'),
('12345678009', '2025-11-28 19:26:24', 'Chưa cập nhật'),
('12345678010', '2025-11-28 19:26:25', 'Chưa cập nhật'),
('12345678701', '2025-11-28 12:23:11', 'Chưa cập nhật'),
('12345678771', '2025-11-28 12:23:12', 'Chưa cập nhật'),
('12345678901', '2025-11-25 12:12:39', 'Chưa cập nhật');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chamdiem`
--

CREATE TABLE `chamdiem` (
  `MaCham` int(11) NOT NULL,
  `MaDeTai` int(11) DEFAULT NULL,
  `MaTienDo` int(11) DEFAULT NULL,
  `MaSV` varchar(255) NOT NULL,
  `MaGV` varchar(20) DEFAULT NULL,
  `Diem` decimal(4,2) DEFAULT NULL,
  `NhanXet` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NgayCham` datetime DEFAULT NULL,
  `TrangThai` varchar(50) DEFAULT 'Chưa xác nhận',
  `DiemCuoi` decimal(4,2) DEFAULT NULL,
  `DaXem` tinyint(1) NOT NULL DEFAULT 0,
  `VaiTro` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chamdiem`
--

INSERT INTO `chamdiem` (`MaCham`, `MaDeTai`, `MaTienDo`, `MaSV`, `MaGV`, `Diem`, `NhanXet`, `NgayCham`, `TrangThai`, `DiemCuoi`, `DaXem`, `VaiTro`) VALUES
(19, 5, NULL, '002245005', 'GV001', 8.40, 'Ổn', '2025-11-21 15:18:23', 'Đã duyệt', 8.70, 0, NULL),
(20, 5, NULL, '002245005', 'GV006', 9.00, NULL, '2025-11-21 15:19:02', 'Đã duyệt', 8.70, 0, 'GVHD,GVPB'),
(21, 6, NULL, '0022425001', 'GV001', 8.50, 'Tốt', '2025-11-24 15:16:56', 'Đã duyệt', 8.75, 0, NULL),
(22, 6, NULL, '0022425001', 'GV005', 9.00, NULL, '2025-11-24 15:17:23', 'Đã duyệt', 8.75, 0, NULL),
(23, 4, NULL, '002245004', 'GV006', 8.60, NULL, '2025-11-24 15:20:51', 'Đã duyệt', 8.55, 0, NULL),
(24, 4, NULL, '002245004', 'GV001', 8.50, NULL, '2025-11-24 15:21:34', 'Đã duyệt', 8.55, 0, NULL),
(27, 13, NULL, '002245006', 'GV006', 9.00, NULL, '2025-11-27 12:34:24', 'Đã duyệt', 9.50, 0, NULL),
(28, 13, NULL, '002245006', 'GV008', 10.00, NULL, '2025-11-27 12:37:35', 'Đã duyệt', 9.50, 0, NULL),
(45, 40, 22, '002245015', 'GV010', 8.60, 'tốt', '2025-12-14 21:36:52', 'Đã duyệt', 8.70, 1, NULL),
(46, 40, 22, '002245021', 'GV010', 8.70, 'tốt', '2025-12-14 21:37:05', 'Đã duyệt', 8.73, 1, NULL),
(47, 40, 24, '002245015', 'GV010', 8.40, NULL, '2025-12-14 21:37:14', 'Đã duyệt', 8.70, 1, NULL),
(48, 40, 24, '002245021', 'GV010', 8.60, NULL, '2025-12-14 21:37:21', 'Đã duyệt', 8.73, 1, NULL),
(49, 40, NULL, '002245015', 'GV010', 9.00, NULL, '2025-12-14 21:40:48', 'Đã duyệt', 8.74, 1, NULL),
(50, 40, NULL, '002245021', 'GV010', 9.00, NULL, '2025-12-14 21:40:53', 'Đã duyệt', 8.74, 1, NULL),
(51, 40, NULL, '002245015', 'GV002', 8.80, NULL, '2025-12-14 21:41:07', 'Đã duyệt', 8.74, 1, NULL),
(52, 40, NULL, '002245021', 'GV002', 8.60, NULL, '2025-12-14 21:41:22', 'Đã duyệt', 8.74, 1, NULL),
(53, 7, NULL, '0022425002', 'GV002', 8.50, NULL, '2025-12-16 16:25:31', 'Đã duyệt', 8.75, 1, NULL),
(54, 7, NULL, '0022425002', 'GV005', 9.00, NULL, '2025-12-16 16:26:18', 'Đã duyệt', 8.75, 1, NULL),
(55, 46, NULL, '002245016', 'GV009', 8.80, NULL, '2025-12-16 22:32:28', 'Chờ duyệt', NULL, 1, NULL),
(56, 46, NULL, '002245016', 'GV001', 9.00, NULL, '2025-12-16 22:34:01', 'Chờ duyệt', NULL, 1, NULL),
(57, 17, NULL, '002245023', 'GV001', 9.80, NULL, '2025-12-16 23:15:55', 'Đã duyệt', 9.14, 1, NULL),
(58, 17, NULL, '002245023', 'GV006', 9.00, NULL, '2025-12-16 23:05:21', 'Đã duyệt', 9.14, 1, NULL),
(59, 47, NULL, '002245013', 'GV006', 8.80, 'NHÌN CHUNG THÌ ỔN', '2025-12-17 11:40:53', 'Đã duyệt', 8.74, 1, NULL),
(60, 47, NULL, '002245013', 'GV016', 9.00, NULL, '2025-12-17 11:41:11', 'Đã duyệt', 8.74, 1, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_conversations`
--

CREATE TABLE `chat_conversations` (
  `id` int(10) UNSIGNED NOT NULL,
  `MaSV` varchar(20) DEFAULT NULL,
  `MaGV` varchar(20) NOT NULL,
  `MaDeTai` int(11) DEFAULT NULL,
  `last_message_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `MaSV`, `MaGV`, `MaDeTai`, `last_message_at`, `created_at`, `updated_at`) VALUES
(2, '002245001', 'GV004', NULL, '2025-12-02 20:58:04', '2025-12-02 11:42:13', '2025-12-02 13:58:04'),
(3, '0022223001', 'GV026', NULL, '2025-12-03 21:11:43', '2025-12-03 13:44:35', '2025-12-03 14:11:43'),
(5, '002245021', 'GV006', 17, NULL, '2025-12-13 15:30:57', '2025-12-13 15:30:57'),
(7, NULL, 'GV010', 40, '2025-12-13 23:28:54', '2025-12-13 15:42:51', '2025-12-13 16:28:54'),
(8, NULL, 'GV020', NULL, NULL, '2025-12-14 12:51:51', '2025-12-14 12:51:51'),
(9, '002245016', 'GV009', 46, '2025-12-16 22:53:37', '2025-12-16 14:53:09', '2025-12-16 15:53:37'),
(10, '002245023', 'GV006', 17, NULL, '2025-12-16 15:55:20', '2025-12-16 15:55:20'),
(11, '002245013', 'GV016', 47, '2025-12-17 11:30:01', '2025-12-17 04:29:44', '2025-12-17 04:30:01'),
(12, '002245020', 'GV020', 50, NULL, '2025-12-17 09:36:18', '2025-12-17 09:36:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `conversation_id` int(10) UNSIGNED NOT NULL,
  `sender_type` enum('SinhVien','GiangVien') NOT NULL,
  `sender_id` varchar(20) NOT NULL,
  `message` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_type`, `sender_id`, `message`, `file_path`, `file_name`, `is_read`, `created_at`, `updated_at`) VALUES
(4, 2, 'SinhVien', '002245001', 'dạ chào cô ạ', NULL, NULL, 1, '2025-12-02 12:54:20', '2025-12-02 12:54:32'),
(5, 2, 'GiangVien', 'GV004', 'có việc gì không em', NULL, NULL, 1, '2025-12-02 12:54:43', '2025-12-02 12:54:55'),
(6, 2, 'SinhVien', '002245001', 'cô xem giúp e file với đã ổn chưa vậy cô', 'storage/chat_files/1764680133_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', '1764680133_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 1, '2025-12-02 12:55:34', '2025-12-02 12:55:57'),
(21, 2, 'GiangVien', 'GV004', 'ok e', NULL, NULL, 1, '2025-12-02 13:57:49', '2025-12-02 14:02:47'),
(22, 2, 'GiangVien', 'GV004', 'tót r', NULL, NULL, 1, '2025-12-02 13:57:55', '2025-12-02 14:02:47'),
(23, 2, 'GiangVien', 'GV004', 'nộp đi nhé', NULL, NULL, 1, '2025-12-02 13:58:04', '2025-12-02 14:02:47'),
(24, 3, 'SinhVien', '0022223001', 'chào thầy ạ', NULL, NULL, 1, '2025-12-03 13:48:33', '2025-12-03 14:11:39'),
(25, 3, 'SinhVien', '0022223001', 'thầy duyệt giúp e với', NULL, NULL, 1, '2025-12-03 14:10:36', '2025-12-03 14:11:39'),
(26, 3, 'GiangVien', 'GV026', 'ok em', NULL, NULL, 0, '2025-12-03 14:11:43', '2025-12-03 14:11:43'),
(27, 7, 'SinhVien', '002245015', 'alo', NULL, NULL, 1, '2025-12-13 16:20:23', '2025-12-13 16:24:53'),
(28, 7, 'GiangVien', 'GV010', 'SAO V', NULL, NULL, 0, '2025-12-13 16:24:58', '2025-12-13 16:24:58'),
(29, 7, 'GiangVien', 'GV010', 'CÓ VC GÌ', NULL, NULL, 0, '2025-12-13 16:28:54', '2025-12-13 16:28:54'),
(30, 9, 'SinhVien', '002245016', 'cảm ơn cô ạ', NULL, NULL, 1, '2025-12-16 15:53:37', '2025-12-16 15:53:45'),
(31, 11, 'SinhVien', '002245013', 'CHÀO THẦY', NULL, NULL, 0, '2025-12-17 04:30:01', '2025-12-17 04:30:01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `detai`
--

CREATE TABLE `detai` (
  `MaDeTai` int(11) NOT NULL,
  `TenDeTai` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `MoTa` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `LinhVuc` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NamHoc` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `LoaiDeTai` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Cá nhân',
  `TrangThai` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Chưa duyệt',
  `MaGV` varchar(20) DEFAULT NULL,
  `MaCB` varchar(20) DEFAULT NULL,
  `MaNamHoc` int(11) DEFAULT NULL,
  `DeadlineBaoCao` datetime DEFAULT NULL,
  `MaKhoa` int(11) DEFAULT NULL,
  `MaNganh` int(11) DEFAULT NULL,
  `SoLuongThanhVien` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `detai`
--

INSERT INTO `detai` (`MaDeTai`, `TenDeTai`, `MoTa`, `LinhVuc`, `NamHoc`, `LoaiDeTai`, `TrangThai`, `MaGV`, `MaCB`, `MaNamHoc`, `DeadlineBaoCao`, `MaKhoa`, `MaNganh`, `SoLuongThanhVien`) VALUES
(2, 'Website bán hàng trang sức', 'Ứng dụng PHP + MySQL + Tailwind', 'Lập trình Web', '2024-2025', 'Cá nhân', 'Đã hoàn thành', 'GV002', 'CB001', 1, NULL, NULL, NULL, 1),
(4, 'Xây dựng website quản lý cửa hàng trang sức', 'Xây dựng website quản lý cửa hàng trang sức', 'Công nghệ thông tin', '2024-2025', 'Cá nhân', 'Đã hoàn thành', 'GV001', 'CB001', 1, NULL, NULL, NULL, 1),
(5, 'Ứng dụng AI nhận diện khuôn mặt sinh viên', 'Ứng dụng AI nhận diện khuôn mặt sinh viên', 'Công nghệ thông tin', '2024-2025', 'Cá nhân', 'Đã hoàn thành', 'GV001', NULL, 1, NULL, NULL, NULL, 1),
(6, 'Phân loại côn trùng', 'Phân loại côn trùng', 'Ứng dụng di động', '2024-2025', 'Cá nhân', 'Đã hoàn thành', 'GV005', NULL, 2, '2025-11-21 02:06:00', NULL, NULL, 1),
(7, 'Ứng dụng đọc sách', 'zdvxdcv', 'Lập trình Web', '2024-2025', 'Cá nhân', 'Đã hoàn thành', 'GV002', NULL, 1, '2025-11-25 00:00:00', NULL, NULL, 1),
(10, 'Xây dựng hệ thống quản lý học tập trực tuyến (E-Learning) hỗ trợ kiểm tra, đánh giá và chấm điểm tự động.', 'Xây dựng hệ thống quản lý học tập trực tuyến (E-Learning) hỗ trợ kiểm tra, đánh giá và chấm điểm tự động.', 'Công nghệ phần mềm', '2024-2025', 'Cá nhân', 'Đã duyệt', 'GV006', 'CB001', 1, '2025-12-06 14:31:00', NULL, NULL, 1),
(13, 'Xây dựng website Quản lý một shop mỹ phẩm', '* Yêu cầu:\r\n  - Phân tích thiết kế Cơ sở dữ liệu \r\n  - Phần quyền người dùng \r\n  - Quản lý thông tin\r\n  - Cập nhật dữ liệu: Thêm, Sửa, Xóa, Tìm kiếm thông tin\r\n  - Thống kê dữ liệu (Report, File pdf, Excel)\r\n  * Công nghệ: CS hoặc CC, HTML, CSS, Bootstrap, JS, ASP.NET (C#), SQL Server,…', 'Lập trình Web', '2022-2023', 'Đồ án Môn học', 'Đã hoàn thành', 'GV006', NULL, 1, '2025-11-11 18:42:00', 1, 2, 1),
(17, 'Phân tích so sánh hiệu quả của các mô hình Dịch máy Nơ-ron (NMT) cho cặp ngôn ngữ Việt - Anh', 'Thực hiện so sánh hiệu suất dịch thuật của các kiến trúc Transformer, RNN và CNN trong việc chuyển ngữ giữa tiếng Việt và tiếng Anh.', 'Ngoại ngữ / Khoa học máy tính', '2024-2025', 'Nghiên cứu Khoa học', 'Đã hoàn thành', 'GV006', NULL, 1, NULL, NULL, NULL, 1),
(18, 'Thiết kế và thi công hệ thống chiếu sáng tự động sử dụng cảm biến ánh sáng và vi điều khiển', 'Xây dựng mạch điều khiển tự động bật/tắt và điều chỉnh cường độ ánh sáng dựa trên mức ánh sáng môi trường thực tế, tối ưu hóa tiêu thụ điện năng.', 'Công nghệ kỹ thuật', '2022-2023', 'Đồ án Môn học', 'Chưa duyệt', 'GV006', NULL, 1, NULL, 1, 10, 1),
(19, 'Ảnh hưởng của biến đổi khí hậu đến năng suất lúa tại Đồng bằng sông Cửu Long', 'Phân tích dữ liệu khí hậu lịch sử và dự báo để đánh giá tác động của nhiệt độ, lượng mưa, và xâm nhập mặn lên các giống lúa chủ lực.', 'Nông nghiệp, Tài nguyên và môi trường', '2022-2023', 'Nghiên cứu Ứng dụng', 'Chưa duyệt', 'GV006', NULL, 1, NULL, 7, 10, 1),
(20, 'Xây dựng bộ công cụ phát hiện và cảnh báo tấn công giả mạo (Phishing)', 'Phát triển một thuật toán sử dụng học máy để phân loại và cảnh báo người dùng về các email hoặc trang web có dấu hiệu lừa đảo.', 'An ninh mạng', '2022-2023', 'Đồ án Môn học', 'Chưa duyệt', 'GV006', NULL, 1, NULL, 1, 6, 1),
(21, 'Nghiên cứu áp dụng phương pháp Bàn học tập lật ngược (Flipped Classroom) trong giảng dạy môn Toán cấp THPT', 'Đánh giá hiệu quả của phương pháp Flipped Classroom so với phương pháp truyền thống đối với kết quả học tập và mức độ hứng thú của học sinh.', 'Sư phạm toán tin', '2022-2023', 'Đồ án Môn học', 'Chưa duyệt', 'GV006', NULL, 1, '2025-12-17 23:00:00', 4, 7, 1),
(31, 'Ứng dụng AI trong chẩn đoán', 'Xây dựng prototype...', NULL, '2024-2025', 'Cá nhân', 'Chưa duyệt', 'GV013', NULL, 1, NULL, 12, 9, 1),
(32, 'Phân tích Công nghệ Khoa học', 'Dự báo doanh thu...', NULL, '2024-2025', 'Cá nhân', 'Chưa duyệt', 'GV013', NULL, 1, NULL, 13, 10, 1),
(38, 'Phân tích so sánh hiệu quả của các mô hình Dịch máy Nơ-ron (NMT) cho cặp ngôn ngữ Việt - Anh', 'Thực hiện so sánh hiệu suất dịch thuật của các kiến trúc Transformer, RNN và CNN trong việc chuyển ngữ giữa tiếng Việt và tiếng Anh.', NULL, '2024-2025', 'Nghiên cứu Khoa học', 'Chưa duyệt', 'GV010', NULL, 3, NULL, 8, 2, 1),
(39, 'Xây dựng hệ thống quản lý học tập trực tuyến (E-Learning) hỗ trợ kiểm tra, đánh giá và chấm điểm tự động', 'Xây dựng hệ thống quản lý học tập trực tuyến hỗ trợ kiểm tra, đánh giá và chấm điểm tự động.', NULL, '2024-2025', 'Đồ án Môn học', 'Mở đăng ký', 'GV010', NULL, 3, NULL, 8, 2, 3),
(40, 'Ứng dụng AI trong chẩn đoán bệnh qua hình ảnh y tế', 'Nghiên cứu và phát triển mô hình AI để hỗ trợ chẩn đoán bệnh từ hình ảnh X-quang và CT scan.', 'Ứng dụng', '2024-2025', 'Cá nhân', 'Đã hoàn thành', 'GV010', NULL, 3, '2025-12-23 00:00:00', 8, 14, 2),
(46, 'cvxcvx', 'cxvxvx', NULL, '2024-2025', 'Cá nhân', 'Đã hoàn thành', 'GV009', NULL, 3, '2025-12-18 21:54:00', 1, 1, 1),
(47, 'BVBVN', 'CVCBVCBVB', NULL, '2024-2025', 'Cá nhân', 'Đã hoàn thành', 'GV016', NULL, 3, '2025-12-13 11:33:00', 1, 1, 1),
(49, 'Xây dựng hệ thống quản lý điểm rèn luyện sinh viên', 'Xây dựng hệ thống quản lý, chấm điểm và thống kê điểm rèn luyện của sinh viên theo từng học kỳ.', NULL, '2024-2025', 'Đồ án Môn học', 'Chưa duyệt', 'GV016', NULL, 3, NULL, 8, 20, 2),
(50, 'bvcnbvnb v', 'cccccbcxbcv', NULL, '2024-2025', 'Cá nhân', 'Mở đăng ký', 'GV020', NULL, 3, '2025-12-19 16:36:00', 1, 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `detai_sinhvien`
--

CREATE TABLE `detai_sinhvien` (
  `MaDeTai` int(11) NOT NULL,
  `MaSV` varchar(20) NOT NULL,
  `VaiTro` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Thành viên',
  `TrangThai` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Đang thực hiện'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `detai_sinhvien`
--

INSERT INTO `detai_sinhvien` (`MaDeTai`, `MaSV`, `VaiTro`, `TrangThai`) VALUES
(2, '002245003', 'Sinh viên', 'Đang thực hiện'),
(4, '002245004', 'Sinh viên', 'Đang thực hiện'),
(5, '002245005', 'Sinh viên', 'Đang thực hiện'),
(6, '0022425001', 'Sinh viên', 'Đang thực hiện'),
(7, '0022425002', 'Sinh viên', 'Đang thực hiện'),
(13, '002245006', 'Sinh viên', 'Đang thực hiện'),
(17, '002245023', 'Sinh viên', 'Đang thực hiện'),
(40, '002245015', 'Sinh viên', 'Đang thực hiện'),
(40, '002245021', 'Sinh viên', 'Đang thực hiện'),
(46, '002245016', 'Sinh viên', 'Đang thực hiện'),
(47, '002245013', 'Sinh viên', 'Đang thực hiện'),
(50, '002245020', 'Sinh viên', 'Đang thực hiện');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `path` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `size` bigint(20) DEFAULT 0,
  `extension` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `file`
--

INSERT INTO `file` (`id`, `name`, `path`, `type`, `size`, `extension`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'BaoCaoLan1.pdf', '/uploads/BaoCaoLan1.pdf', 'application/pdf', 102400, 'pdf', 0, '2025-11-07 19:14:28', '2025-11-07 19:14:28'),
(2, 'ERD.png', '/uploads/ERD.png', 'image/png', 51200, 'png', 0, '2025-11-07 19:14:28', '2025-11-07 19:14:28'),
(3, 'khoa_import.xlsx', 'excel/1764052435_khoa_import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8624, 'xlsx', 0, '2025-11-25 06:33:55', '2025-11-25 06:33:55'),
(4, 'giangvien_export_1.xlsx', 'excel/1764070515_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9551, 'xlsx', 0, '2025-11-25 11:35:15', '2025-11-25 11:35:15'),
(5, 'giangvien_export_1.xlsx', 'excel/1764071032_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9551, 'xlsx', 0, '2025-11-25 11:43:52', '2025-11-25 11:43:52'),
(6, 'giangvien_export_1.xlsx', 'excel/1764071181_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9551, 'xlsx', 0, '2025-11-25 11:46:21', '2025-11-25 11:46:21'),
(7, 'giangvien_export_1.xlsx', 'excel/1764071225_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9551, 'xlsx', 0, '2025-11-25 11:47:05', '2025-11-25 11:47:05'),
(8, 'giangvien_export_1.xlsx', 'excel/1764071348_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9551, 'xlsx', 0, '2025-11-25 11:49:08', '2025-11-25 11:49:08'),
(9, 'giangvien_export_1.xlsx', 'excel/1764071439_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9551, 'xlsx', 0, '2025-11-25 11:50:39', '2025-11-25 11:50:39'),
(10, 'giangvien_export_1.xlsx', 'excel/1764071689_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9551, 'xlsx', 0, '2025-11-25 11:54:49', '2025-11-25 11:54:49'),
(11, 'sinhvien_export (1).xlsx', 'excel/1764072175_sinhvien_export (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9058, 'xlsx', 0, '2025-11-25 12:02:55', '2025-11-25 12:02:55'),
(12, 'giangvien_export_1.xlsx', 'excel/1764072316_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9551, 'xlsx', 0, '2025-11-25 12:05:16', '2025-11-25 12:05:16'),
(13, 'giangvien_export_1.xlsx', 'excel/1764072434_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9551, 'xlsx', 0, '2025-11-25 12:07:14', '2025-11-25 12:07:14'),
(14, 'giangvien_export_1.xlsx', 'excel/1764072607_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9572, 'xlsx', 0, '2025-11-25 12:10:07', '2025-11-25 12:10:07'),
(15, 'giangvien_export_1.xlsx', 'excel/1764072759_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9572, 'xlsx', 0, '2025-11-25 12:12:39', '2025-11-25 12:12:39'),
(16, 'sinhvien_mau (1).xlsx', 'excel/1764072996_sinhvien_mau (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8392, 'xlsx', 0, '2025-11-25 12:16:36', '2025-11-25 12:16:36'),
(17, 'sinhvien_mau (1).xlsx', 'excel/1764075362_sinhvien_mau (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8392, 'xlsx', 0, '2025-11-25 12:56:02', '2025-11-25 12:56:02'),
(18, 'sinhvien_mau (1).xlsx', 'excel/1764075543_sinhvien_mau (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8431, 'xlsx', 0, '2025-11-25 12:59:03', '2025-11-25 12:59:03'),
(19, '0022411571_PHAMMYTIEN_LAICHAU.pdf', 'baocao/1764152949_0022411571_PHAMMYTIEN_LAICHAU.pdf', 'application/pdf', 1166566, 'pdf', 0, '2025-11-26 10:29:09', '2025-11-26 10:29:09'),
(20, '0022411571_PHAMMYTIEN_LAICHAU.pdf', 'baocao/1764153504_0022411571_PHAMMYTIEN_LAICHAU.pdf', 'application/pdf', 1166566, 'pdf', 0, '2025-11-26 10:38:24', '2025-11-26 10:38:24'),
(21, '1764245488_code_New.rar', 'storage/baocao/code/1764245488_code_New.rar', 'application/x-rar', 68897, 'rar', 0, '2025-11-27 12:11:28', '2025-11-27 12:11:28'),
(22, '1764245511_code_New.rar', 'storage/baocao/code/1764245511_code_New.rar', 'application/x-rar', 68897, 'rar', 0, '2025-11-27 12:11:51', '2025-11-27 12:11:51'),
(23, '1764245569_code_New.rar', 'storage/baocao/code/1764245569_code_New.rar', 'application/x-rar', 68897, 'rar', 0, '2025-11-27 12:12:49', '2025-11-27 12:12:49'),
(24, '1764245907_code_RSA.rar', 'storage/baocao/code/1764245907_code_RSA.rar', 'application/x-rar', 1186, 'rar', 0, '2025-11-27 12:18:27', '2025-11-27 12:18:27'),
(25, '1764245982_code_RSA.rar', 'storage/baocao/code/1764245982_code_RSA.rar', 'application/x-rar', 1186, 'rar', 0, '2025-11-27 12:19:42', '2025-11-27 12:19:42'),
(26, '1764246212_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'storage/baocao/1764246212_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'application/pdf', 171716, 'pdf', 0, '2025-11-27 12:23:32', '2025-11-27 12:23:32'),
(27, '1764246212_code_QLBH.rar', 'storage/baocao/code/1764246212_code_QLBH.rar', 'application/x-rar', 1273740, 'rar', 0, '2025-11-27 12:23:32', '2025-11-27 12:23:32'),
(28, 'giangvien_export_1.xlsx', 'excel/1764307389_giangvien_export_1.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9589, 'xlsx', 0, '2025-11-28 12:23:09', '2025-11-28 12:23:09'),
(29, 'sinhvien_mau (1).xlsx', 'excel/1764308295_sinhvien_mau (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8445, 'xlsx', 0, '2025-11-28 12:38:15', '2025-11-28 12:38:15'),
(30, 'sinhvien_mau (1).xlsx', 'excel/1764309656_sinhvien_mau (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8445, 'xlsx', 0, '2025-11-28 13:00:56', '2025-11-28 13:00:56'),
(31, 'sinhvien_mau (1).xlsx', 'excel/1764310419_sinhvien_mau (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8487, 'xlsx', 0, '2025-11-28 13:13:39', '2025-11-28 13:13:39'),
(32, 'sinhvien_mau (1).xlsx', 'excel/1764310514_sinhvien_mau (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8495, 'xlsx', 0, '2025-11-28 13:15:14', '2025-11-28 13:15:14'),
(33, 'sinhvien_mau (1).xlsx', 'excel/1764310659_sinhvien_mau (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8495, 'xlsx', 0, '2025-11-28 13:17:39', '2025-11-28 13:17:39'),
(34, 'mau_import_canbo.xlsx', 'excel/1764314883_mau_import_canbo.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8700, 'xlsx', 0, '2025-11-28 14:28:03', '2025-11-28 14:28:03'),
(35, 'danh_sach_sinh_vien.xlsx', 'excel/1764332213_danh_sach_sinh_vien.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9337, 'xlsx', 0, '2025-11-28 19:16:53', '2025-11-28 19:16:53'),
(36, 'danh_sach_giang_vien.xlsx', 'excel/1764332778_danh_sach_giang_vien.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8820, 'xlsx', 0, '2025-11-28 19:26:18', '2025-11-28 19:26:18'),
(37, 'giangvien.xlsx', 'excel/1764334076_giangvien.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9555, 'xlsx', 0, '2025-11-28 19:47:56', '2025-11-28 19:47:56'),
(38, 'detai.xlsx', 'excel/1764338050_detai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9274, 'xlsx', 0, '2025-11-28 20:54:10', '2025-11-28 20:54:10'),
(39, '1764341945_B1.pdf', 'storage/baocao/1764341945_B1.pdf', 'application/pdf', 254137, 'pdf', 0, '2025-11-28 21:59:06', '2025-11-28 21:59:06'),
(40, '1764342175_B1.pdf', 'baocao/1764342175_B1.pdf', 'application/pdf', 254137, 'pdf', 0, '2025-11-28 22:02:55', '2025-11-28 22:02:55'),
(41, '1764342915_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'storage/baocao/1764342915_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'application/pdf', 586162, 'pdf', 0, '2025-11-28 22:15:15', '2025-11-28 22:15:15'),
(42, '1764342915_code_QLCH.rar', 'storage/baocao/code/1764342915_code_QLCH.rar', 'application/x-rar', 566153, 'rar', 0, '2025-11-28 22:15:15', '2025-11-28 22:15:15'),
(43, '1764343198_code_RSA.rar', 'baocao/code/1764343198_code_RSA.rar', 'application/x-rar', 1186, 'rar', 0, '2025-11-28 22:19:59', '2025-11-28 22:19:59'),
(44, '1764345353_code_ThucHanh.rar', 'baocao/code/1764345353_code_ThucHanh.rar', 'application/x-rar', 1748174, 'rar', 0, '2025-11-28 22:55:53', '2025-11-28 22:55:53'),
(45, '1764345403_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'storage/baocao/1764345403_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'application/pdf', 586162, 'pdf', 0, '2025-11-28 22:56:43', '2025-11-28 22:56:43'),
(46, '1764345403_code_HCSTT.rar', 'storage/baocao/code/1764345403_code_HCSTT.rar', 'application/x-rar', 3774977, 'rar', 0, '2025-11-28 22:56:43', '2025-11-28 22:56:43'),
(47, 'danh_sach_giang_vien (1).xlsx', 'excel/1764425585_danh_sach_giang_vien (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9142, 'xlsx', 0, '2025-11-29 21:13:05', '2025-11-29 21:13:05'),
(48, 'danh_sach_sinh_vien.xlsx', 'excel/1764426116_danh_sach_sinh_vien.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9278, 'xlsx', 0, '2025-11-29 21:21:56', '2025-11-29 21:21:56'),
(49, 'danh_sach_sinh_vien.xlsx', 'excel/1764426303_danh_sach_sinh_vien.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8999, 'xlsx', 0, '2025-11-29 21:25:03', '2025-11-29 21:25:03'),
(50, 'mau_import_canbo.xlsx', 'excel/1764426849_mau_import_canbo.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9241, 'xlsx', 0, '2025-11-29 21:34:09', '2025-11-29 21:34:09'),
(51, 'danh_sach_giang_vien (1).xlsx', 'excel/1764427005_danh_sach_giang_vien (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8908, 'xlsx', 0, '2025-11-29 21:36:45', '2025-11-29 21:36:45'),
(52, 'danh_sach_sinh_vien.xlsx', 'excel/1764427084_danh_sach_sinh_vien.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8980, 'xlsx', 0, '2025-11-29 21:38:04', '2025-11-29 21:38:04'),
(53, 'danh_sach_detai.xlsx', 'excel/1764428905_danh_sach_detai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 4856, 'xlsx', 0, '2025-11-29 22:08:25', '2025-11-29 22:08:25'),
(54, 'danh_sach_detai.xlsx', 'excel/1764428951_danh_sach_detai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 4856, 'xlsx', 0, '2025-11-29 22:09:11', '2025-11-29 22:09:11'),
(55, 'detai.xlsx', 'excel/1764429243_detai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9413, 'xlsx', 0, '2025-11-29 22:14:03', '2025-11-29 22:14:03'),
(56, 'detai.xlsx', 'excel/1764429267_detai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9413, 'xlsx', 0, '2025-11-29 22:14:27', '2025-11-29 22:14:27'),
(57, 'detai.xlsx', 'excel/1764429314_detai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9408, 'xlsx', 0, '2025-11-29 22:15:14', '2025-11-29 22:15:14'),
(58, 'detai.xlsx', 'excel/1764429511_detai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9408, 'xlsx', 0, '2025-11-29 22:18:31', '2025-11-29 22:18:31'),
(59, 'detai_3.xlsx', 'excel/1764429756_detai_3.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 5927, 'xlsx', 0, '2025-11-29 22:22:36', '2025-11-29 22:22:36'),
(60, 'mau_detai.xlsx', 'excel/1764430273_mau_detai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 5658, 'xlsx', 0, '2025-11-29 22:31:13', '2025-11-29 22:31:13'),
(61, 'mau_detai.xlsx', 'excel/1764430409_mau_detai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8468, 'xlsx', 0, '2025-11-29 22:33:29', '2025-11-29 22:33:29'),
(62, '1764431410_code_1764245982_code_RSA (1).rar', 'baocao/code/1764431410_code_1764245982_code_RSA (1).rar', 'application/x-rar', 1186, 'rar', 0, '2025-11-29 22:50:10', '2025-11-29 22:50:10'),
(63, '1764431556_PHẠM MỸ TIÊN.pdf', 'storage/baocao/1764431556_PHẠM MỸ TIÊN.pdf', 'application/pdf', 205121, 'pdf', 0, '2025-11-29 22:52:36', '2025-11-29 22:52:36'),
(64, '1764431556_code_New.rar', 'storage/baocao/code/1764431556_code_New.rar', 'application/x-rar', 68897, 'rar', 0, '2025-11-29 22:52:36', '2025-11-29 22:52:36'),
(65, '1764770078_code_QLCH.rar', 'baocao/code/1764770078_code_QLCH.rar', 'application/x-rar', 566153, 'rar', 0, '2025-12-03 20:54:38', '2025-12-03 20:54:38'),
(66, '1764771565_PHƯƠNG PHÁP.docx', 'storage/baocao/1764771565_PHƯƠNG PHÁP.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 122356, 'docx', 0, '2025-12-03 21:19:25', '2025-12-03 21:19:25'),
(67, '1764771565_code_New.rar', 'storage/baocao/code/1764771565_code_New.rar', 'application/x-rar', 68897, 'rar', 0, '2025-12-03 21:19:25', '2025-12-03 21:19:25'),
(68, 'danh_sach_de_tai.xlsx', 'excel/1765634958_danh_sach_de_tai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 5511, 'xlsx', 0, '2025-12-13 21:09:18', '2025-12-13 21:09:18'),
(69, 'danh_sach_giang_vien (1).xlsx', 'excel/1765715534_danh_sach_giang_vien (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8908, 'xlsx', 0, '2025-12-14 19:32:14', '2025-12-14 19:32:14'),
(70, 'danh_sach_sinh_vien.xlsx', 'excel/1765715933_danh_sach_sinh_vien.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8979, 'xlsx', 0, '2025-12-14 19:38:53', '2025-12-14 19:38:53'),
(71, 'danh_sach_de_tai.xlsx', 'excel/1765716553_danh_sach_de_tai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8542, 'xlsx', 0, '2025-12-14 19:49:13', '2025-12-14 19:49:13'),
(72, '1765719667_1.docx', 'storage/baocao/1765719667_1.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 19753, 'docx', 0, '2025-12-14 20:41:07', '2025-12-14 20:41:07'),
(73, '1765719667_code_RSA.rar', 'storage/baocao/code/1765719667_code_RSA.rar', 'application/x-rar', 1186, 'rar', 0, '2025-12-14 20:41:07', '2025-12-14 20:41:07'),
(74, 'TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'baocao/1765722690_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'application/pdf', 171716, 'pdf', 0, '2025-12-14 21:31:30', '2025-12-14 21:31:30'),
(75, 'TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'baocao/1765722782_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'application/pdf', 171716, 'pdf', 0, '2025-12-14 21:33:02', '2025-12-14 21:33:02'),
(76, 'TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'baocao/1765722804_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'application/pdf', 171716, 'pdf', 0, '2025-12-14 21:33:24', '2025-12-14 21:33:24'),
(77, 'TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'baocao/1765722899_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'application/pdf', 171716, 'pdf', 0, '2025-12-14 21:34:59', '2025-12-14 21:34:59'),
(78, 'RSA.rar', 'code/1765722989_RSA.rar', 'application/x-compressed', 1186, 'rar', 0, '2025-12-14 21:36:29', '2025-12-14 21:36:29'),
(79, '1765723136_Tự học.docx', 'storage/baocao/1765723136_Tự học.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 44173, 'docx', 0, '2025-12-14 21:38:56', '2025-12-14 21:38:56'),
(80, '1765723136_code_RSA.rar', 'storage/baocao/code/1765723136_code_RSA.rar', 'application/x-rar', 1186, 'rar', 0, '2025-12-14 21:38:56', '2025-12-14 21:38:56'),
(81, 'danh_sach_giang_vien (1).xlsx', 'excel/1765865933_danh_sach_giang_vien (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8904, 'xlsx', 0, '2025-12-16 13:18:53', '2025-12-16 13:18:53'),
(82, 'danh_sach_sinh_vien.xlsx', 'excel/1765866140_danh_sach_sinh_vien.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8983, 'xlsx', 0, '2025-12-16 13:22:20', '2025-12-16 13:22:20'),
(83, 'danh_sach_sinh_vien.xlsx', 'excel/1765868838_danh_sach_sinh_vien.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8983, 'xlsx', 0, '2025-12-16 14:07:18', '2025-12-16 14:07:18'),
(84, 'danh_sach_sinh_vien.xlsx', 'excel/1765869150_danh_sach_sinh_vien.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8983, 'xlsx', 0, '2025-12-16 14:12:30', '2025-12-16 14:12:30'),
(85, 'khoa_export (1).xlsx', 'excel/1765872276_khoa_export (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8567, 'xlsx', 0, '2025-12-16 15:04:36', '2025-12-16 15:04:36'),
(86, 'lop_export.xlsx', 'excel/1765872425_lop_export.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8636, 'xlsx', 0, '2025-12-16 15:07:05', '2025-12-16 15:07:05'),
(87, 'TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'baocao/1765896921_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'application/pdf', 171716, 'pdf', 0, '2025-12-16 21:55:21', '2025-12-16 21:55:21'),
(88, 'TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'baocao/1765898385_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'application/pdf', 171716, 'pdf', 0, '2025-12-16 22:19:45', '2025-12-16 22:19:45'),
(89, '20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'storage/baocao/1765898770_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'application/pdf', 586162, 'pdf', 0, '2025-12-16 22:26:10', '2025-12-16 22:26:10'),
(90, 'RSA.rar', 'storage/code/1765899017_RSA.rar', 'application/x-compressed', 1186, 'rar', 0, '2025-12-16 22:30:17', '2025-12-16 22:30:17'),
(91, '1765899069_PHƯƠNG PHÁP.docx', 'storage/baocao/1765899069_PHƯƠNG PHÁP.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 122356, 'docx', 0, '2025-12-16 22:31:09', '2025-12-16 22:31:09'),
(92, '1765899069_code_RSA.rar', 'storage/baocao/code/1765899069_code_RSA.rar', 'application/x-rar', 1186, 'rar', 0, '2025-12-16 22:31:09', '2025-12-16 22:31:09'),
(93, '20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'storage/baocao/1765900945_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'application/pdf', 586162, 'pdf', 0, '2025-12-16 23:02:25', '2025-12-16 23:02:25'),
(94, 'RSA.rar', 'storage/code/1765900988_RSA.rar', 'application/x-compressed', 1186, 'rar', 0, '2025-12-16 23:03:08', '2025-12-16 23:03:08'),
(95, '1765901033_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'storage/baocao/1765901033_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'application/pdf', 586162, 'pdf', 0, '2025-12-16 23:03:53', '2025-12-16 23:03:53'),
(96, '1765901033_code_RSA.rar', 'storage/baocao/code/1765901033_code_RSA.rar', 'application/x-rar', 1186, 'rar', 0, '2025-12-16 23:03:53', '2025-12-16 23:03:53'),
(97, 'danh_sach_giang_vien (1).xlsx', 'excel/1765943838_danh_sach_giang_vien (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8578, 'xlsx', 0, '2025-12-17 10:57:18', '2025-12-17 10:57:18'),
(98, 'danh_sach_sinh_vien.xlsx', 'excel/1765944040_danh_sach_sinh_vien.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8608, 'xlsx', 0, '2025-12-17 11:00:40', '2025-12-17 11:00:40'),
(99, 'CanBo_Template (1).xlsx', 'excel/1765944526_CanBo_Template (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 4729, 'xlsx', 0, '2025-12-17 11:08:46', '2025-12-17 11:08:46'),
(100, 'danh_sach_de_tai.xlsx', 'excel/1765945716_danh_sach_de_tai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8341, 'xlsx', 0, '2025-12-17 11:28:36', '2025-12-17 11:28:36'),
(101, 'danh_sach_de_tai.xlsx', 'excel/1765945719_danh_sach_de_tai.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 8341, 'xlsx', 0, '2025-12-17 11:28:39', '2025-12-17 11:28:39'),
(102, '20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'storage/baocao/1765945903_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'application/pdf', 586162, 'pdf', 0, '2025-12-17 11:31:43', '2025-12-17 11:31:43'),
(103, 'TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'storage/baocao/1765946267_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'application/pdf', 171716, 'pdf', 0, '2025-12-17 11:37:47', '2025-12-17 11:37:47'),
(104, 'RSA.rar', 'storage/code/1765946277_RSA.rar', 'application/x-compressed', 1186, 'rar', 0, '2025-12-17 11:37:57', '2025-12-17 11:37:57'),
(105, '1765946354_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'storage/baocao/1765946354_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'application/pdf', 171716, 'pdf', 0, '2025-12-17 11:39:15', '2025-12-17 11:39:15'),
(106, '1765946355_code_RSA.rar', 'storage/baocao/code/1765946355_code_RSA.rar', 'application/x-rar', 1186, 'rar', 0, '2025-12-17 11:39:15', '2025-12-17 11:39:15'),
(107, '20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'storage/baocao/1765964287_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', 'application/pdf', 586162, 'pdf', 0, '2025-12-17 16:38:07', '2025-12-17 16:38:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giangvien`
--

CREATE TABLE `giangvien` (
  `MaGV` varchar(20) NOT NULL,
  `TenGV` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `GioiTinh` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NgaySinh` datetime DEFAULT NULL,
  `MaCCCD` varchar(15) DEFAULT NULL,
  `TonGiao` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `Email` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NoiSinh` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `HKTT` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `DanToc` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `HocVi` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `HocHam` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ChuyenNganh` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `MaKhoa` int(11) DEFAULT NULL,
  `MaTK` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `MaNganh` bigint(20) UNSIGNED DEFAULT NULL,
  `MaNamHoc` bigint(20) UNSIGNED DEFAULT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `giangvien`
--

INSERT INTO `giangvien` (`MaGV`, `TenGV`, `GioiTinh`, `NgaySinh`, `MaCCCD`, `TonGiao`, `SDT`, `Email`, `NoiSinh`, `HKTT`, `DanToc`, `HocVi`, `HocHam`, `ChuyenNganh`, `MaKhoa`, `MaTK`, `created_at`, `updated_at`, `MaNganh`, `MaNamHoc`, `HinhAnh`) VALUES
('GV001', 'Lê Minh Tuấn', 'Nam', '1988-12-15 00:00:00', '089123456789', 'Không', '0909000001', 'tuan@uni.edu.vn', 'Tiềng Giang', 'Đồng Tháp', 'Kinh', 'Tiến sĩ', 'PGS', 'Khoa học máy tính', 1, 3, NULL, NULL, 1, 1, NULL),
('GV002', 'Phạm Quang Dũng', 'Nam', '1986-03-10 00:00:00', '079987654321', NULL, '0909000002', 'dung@uni.edu.vn', NULL, NULL, NULL, 'Thạc sĩ', 'GV', 'Công nghệ phần mềm', 1, 4, NULL, NULL, 2, 1, NULL),
('GV003', 'Trần Thanh Bình', 'Nữ', '1988-11-20 00:00:00', '079999888777', NULL, '0909000003', 'binh@uni.edu.vn', NULL, NULL, NULL, 'Thạc sĩ', 'GV', 'Hệ thống thông tin', 1, 5, NULL, NULL, 2, 1, NULL),
('GV004', 'Trần Kim Hương', 'Nữ', '1990-02-14 00:00:00', '088894561591', 'Không', '0321854987', 'huong@gmail.com', 'Tiềng Giang', 'Đồng Tháp', 'Kinh', 'Thạc sĩ', 'Giảng viên', 'Khoa học máy tính', 1, 14, NULL, NULL, 2, 1, 'img/uploads/images/1764158301_123.jpg'),
('GV005', 'Uyên Minh', 'Nữ', '1998-11-25 00:00:00', '087456989524', NULL, '0329654987', 'uminh@gmail.com', NULL, NULL, NULL, 'Tiến sĩ', 'PGS', 'Khoa học máy tính', 1, 15, NULL, NULL, 1, 1, NULL),
('GV006', 'Sang Em', 'Nam', '1999-02-14 00:00:00', '087945625138', NULL, '0369874521', 'sangem@gmail.com', NULL, NULL, NULL, 'Tiến sĩ', 'PGS', 'Khoa học máy tính', 1, 18, NULL, NULL, 1, 1, 'img/uploads/images/1765903027_4444.jpg'),
('GV007', 'Nhật Cường', 'Nam', '1996-02-15 00:00:00', '087756989524', NULL, '0329694987', 'nhatcuong@gmail.com', NULL, NULL, NULL, NULL, NULL, 'Hệ thống thông tin', 2, 19, NULL, NULL, 2, 2, NULL),
('GV008', 'Nguyễn Văn A', 'Nam', '1985-02-15 00:00:00', '12345678901', NULL, '912345678', 'nguyenvana@example.com', NULL, NULL, NULL, 'Thạc sĩ', 'Giảng viên', NULL, 1, 23, NULL, NULL, 1, NULL, NULL),
('GV009', 'Nguyễn Thùy Linh', 'Nữ', '1988-09-12 00:00:00', '089765431236', NULL, '0987654323', 'linh@gmail.com', NULL, NULL, NULL, 'Thạc sĩ', NULL, NULL, 2, 29, NULL, NULL, 3, NULL, NULL),
('GV010', 'Nguyễn Văn Anh Khoa', 'Nam', '1998-03-12 00:00:00', '087954612351', 'Không', '0992345678', 'nguyenvananhkhoa@example.com', 'Đồng Tháp', 'Đồng Tháp', 'Kinh', 'Thạc sĩ', 'Giảng viên', 'Khoa học máy tính', 1, 30, NULL, NULL, 1, 1, NULL),
('GV011', 'Nguyễn Anh Khoa', 'Nam', '1985-02-17 00:00:00', '089123434789', 'Không', '0992345677', 'nguyenanhkhoa@example.com', 'Tiền Giang', 'Tiền Giang', 'Kinh', 'Thạc sĩ', 'Giảng viên', NULL, 1, 31, NULL, NULL, 1, NULL, NULL),
('GV012', 'Đinh Thị Mai Phương', 'Nữ', '1988-09-05 00:00:00', '087345678702', NULL, '0931234567', 'maiphuong@example.edu', NULL, NULL, NULL, 'Thạc sĩ', '', NULL, 3, 59, NULL, NULL, 2, NULL, NULL),
('GV013', 'Trần Đình Việt', 'Nam', '1982-04-10 00:00:00', '083456787013', NULL, '0932345678', 'dinhviet@example.edu', NULL, NULL, NULL, 'Tiến sĩ', 'Giáo Viên', 'Công nghệ phần mềm', 4, 60, NULL, NULL, 1, NULL, NULL),
('GV014', 'Hoàng Văn Duy', 'Nam', '1979-07-25 00:00:00', '083456787014', NULL, '0932345679', 'vanduy@example.edu', NULL, NULL, NULL, 'Thạc sĩ', '', NULL, 2, 61, NULL, NULL, 6, NULL, NULL),
('GV015', 'Nguyễn Thu Trang', 'Nữ', '1990-11-18 00:00:00', '083456787015', NULL, '0932345680', 'thutrang@example.edu', NULL, NULL, NULL, 'Thạc sĩ', '', NULL, 1, 62, NULL, NULL, 5, NULL, NULL),
('GV016', 'Phạm Thanh Tùng', 'Nam', '1975-01-02 00:00:00', '083456787016', NULL, '0932345681', 'thanhtung@example.edu', NULL, NULL, NULL, 'Tiến sĩ', 'PGS', 'Khoa học máy tính', 5, 63, NULL, NULL, 4, NULL, NULL),
('GV017', 'Lê Thị Yến Nhi', 'Nữ', '1986-03-14 00:00:00', '083456787017', NULL, '0932345682', 'yennhi@example.edu', NULL, NULL, NULL, 'Thạc sĩ', '', NULL, 7, 64, NULL, NULL, 3, NULL, NULL),
('GV019', 'Đặng Văn Thiện', 'Nam', '1977-12-11 00:00:00', '083456787019', NULL, '0932345684', 'vanthien@example.edu', NULL, NULL, NULL, 'Tiến sĩ', '', NULL, 4, 66, NULL, NULL, 2, NULL, NULL),
('GV020', 'Bùi Thị Hà Anh', 'Nữ', '1992-05-07 00:00:00', '083456787020', NULL, '0932345685', 'haanh@example.edu', NULL, NULL, NULL, 'Thạc sĩ', '', NULL, 1, 67, NULL, NULL, 6, NULL, NULL),
('GV021', 'Tô Ngọc Minh', 'Nam', '1984-06-22 00:00:00', '083456787021', NULL, '0932345686', 'ngocminh@example.edu', NULL, NULL, NULL, 'Thạc sĩ', '', NULL, 5, 68, NULL, NULL, 5, NULL, NULL),
('GV022', 'Nguyễn Tấn Tài', 'Nam', '1985-09-19 00:00:00', '087896523147', NULL, '0992345670', 'tantai@example.com', NULL, NULL, NULL, 'Thạc sĩ', 'Giảng viên', 'Hệ thống thông tin', 8, 69, NULL, NULL, 1, 1, NULL),
('GV023', 'Hoàng Văn Hậu', 'Nam', '1985-09-17 00:00:00', '087896523147', NULL, '0992345671', 'hvhau@example.com', NULL, NULL, NULL, 'Thạc sĩ', 'Giảng viên', NULL, 4, 70, NULL, NULL, 7, NULL, NULL),
('GV024', 'Trần Thị Minh Anh', 'Nữ', '1989-03-01 00:00:00', '087896523147', NULL, '0992345672', 'minhanh@example.com', NULL, NULL, NULL, 'Thạc sĩ', 'Giảng viên', NULL, 8, 71, NULL, NULL, 1, NULL, NULL),
('GV025', 'Nguyễn Tấn Phúc', 'Nam', '1988-09-19 00:00:00', '087896529147', NULL, '0992345679', 'tanphuc@example.com', NULL, NULL, NULL, 'Thạc sĩ', 'Giảng viên', NULL, 8, 77, NULL, NULL, 1, NULL, NULL),
('GV026', 'Nguyễn Minh Hậu', 'Nam', '1980-02-14 00:00:00', '087456689524', NULL, '0321654989', 'hau@gmail.com', NULL, NULL, NULL, NULL, NULL, 'Quản lý giáo dục', 15, 83, NULL, NULL, 12, 1, NULL),
('GV027', 'Hà Hồng Phúc', 'Nam', '1999-09-19 00:00:00', '087897529187', NULL, '0992345657', 'phuc@example.com', NULL, NULL, NULL, 'Thạc sĩ', 'Giảng viên', NULL, 8, 88, NULL, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khoa`
--

CREATE TABLE `khoa` (
  `MaKhoa` int(11) NOT NULL,
  `TenKhoa` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khoa`
--

INSERT INTO `khoa` (`MaKhoa`, `TenKhoa`) VALUES
(1, 'Công nghệ kỹ thuật'),
(2, 'Ngoại ngữ'),
(3, 'Kinh tế - Luật'),
(4, 'Sư phạm toán tin'),
(5, 'Nông nghiệp, Tài nguyên và môi trường'),
(7, 'Sư phạm KHTN'),
(8, 'Công nghệ thông tin'),
(12, 'Khoa học máy tính'),
(13, 'Quản trị Kinh tế'),
(15, 'Văn hóa du lịch'),
(17, 'Giáo dục tiểu học'),
(18, 'Kinh tế'),
(20, 'cvbbvbcv');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lop`
--

CREATE TABLE `lop` (
  `MaLop` int(11) NOT NULL,
  `TenLop` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `MaKhoa` int(11) DEFAULT NULL,
  `MaNganh` int(11) DEFAULT NULL,
  `MaGV` varchar(20) DEFAULT NULL,
  `MaCB` varchar(20) DEFAULT NULL,
  `MaNamHoc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lop`
--

INSERT INTO `lop` (`MaLop`, `TenLop`, `MaKhoa`, `MaNganh`, `MaGV`, `MaCB`, `MaNamHoc`) VALUES
(1, 'CNTT22A', 1, 1, NULL, NULL, 1),
(2, 'CNTT22B', 1, 2, NULL, NULL, 1),
(3, 'CNTT22C-IT', 1, 2, NULL, NULL, 1),
(4, 'CNTT22D', 1, 1, NULL, NULL, 1),
(5, 'CNTT22A-IT', 1, 2, NULL, NULL, 1),
(6, 'CNTT22B-IT', 1, 2, NULL, NULL, 1),
(7, 'CNTT22E', 1, 2, NULL, NULL, 1),
(8, 'CNTT22C', 1, 1, NULL, NULL, NULL),
(12, 'GDTH22A', 17, NULL, NULL, NULL, NULL),
(13, 'TCNH22A', 18, 16, NULL, NULL, NULL),
(14, 'CNTT22G', 1, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_22_160731_create_files_table', 1),
(5, '2025_11_03_071319_create_roles_table', 1),
(6, '2025_11_04_081552_add_timestamps_to_sinhvien_table', 1),
(7, '2025_11_07_124220_create_cauhinh_hethong_table', 2),
(8, '2025_11_10_141707_add_trangthai_to_baocao_table', 3),
(9, '2025_11_10_160113_add__ma_s_v_to__cham_diem_table', 4),
(11, '2025_11_14_095848_add_timestamps_to_giangvien_table', 5),
(12, '2025_11_14_110614_add_ma_nganh_and_ma_namhoc_to_giangvien_table', 5),
(13, '2025_11_14_111231_update_giangvien_table', 6),
(14, '2025_11_14_135011_add_trangthai_to_chamdiem_table', 7),
(15, '2025_11_14_135944_add_vaitro_to_chamdiem_table', 8),
(16, '2025_11_17_141305_add_masv_to_baocao_table', 9),
(17, '2025_11_21_090903_add_trangthai_to_taikhoan_table', 10),
(18, '2025_11_21_130814_add_trangthai_to_chamdiem_table', 11),
(19, '2025_11_22_154111_add_deadline_to_detai', 12),
(20, '2025_11_22_154156_add_islate_to_baocao', 13),
(21, '2025_11_22_175028_add_deadline_ngaynop_to_tiendo_table', 14),
(22, '2025_11_26_165500_add_file_columns_to_tiendo_table', 15);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `namhoc`
--

CREATE TABLE `namhoc` (
  `MaNamHoc` int(11) NOT NULL,
  `TenNamHoc` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `TrangThai` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `namhoc`
--

INSERT INTO `namhoc` (`MaNamHoc`, `TenNamHoc`, `TrangThai`) VALUES
(1, '2022-2023', 'Hoạt động'),
(2, '2023-2024', 'Hoạt động'),
(3, '2024-2025', 'Hoạt động'),
(4, '2025-2026', 'Dự kiến');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nganh`
--

CREATE TABLE `nganh` (
  `MaNganh` int(11) NOT NULL,
  `TenNganh` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nganh`
--

INSERT INTO `nganh` (`MaNganh`, `TenNganh`) VALUES
(1, 'Khoa học máy tính'),
(2, 'Công nghệ phần mềm'),
(3, 'Công nghệ sinh học'),
(4, 'Kỹ thuật xây dựng'),
(5, 'Công nghệ thực phẩm'),
(6, 'An ninh mạng'),
(7, 'Sư phạm Tin'),
(9, 'Nghiên cứu AI'),
(10, 'Ứng dụng'),
(11, 'Công nghệ phần mềm AI'),
(12, 'Công tác xã hội'),
(14, 'Trí tuệ nhân tạo'),
(16, 'Tài chính ngân hàng'),
(17, 'Kỹ thuật phần mềm'),
(20, 'Hệ thống thông tin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phancong`
--

CREATE TABLE `phancong` (
  `MaPC` int(11) NOT NULL,
  `MaDeTai` int(11) NOT NULL,
  `MaGV` varchar(20) NOT NULL,
  `MaCB` varchar(20) DEFAULT NULL,
  `VaiTro` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Hướng dẫn chính',
  `NgayPhanCong` datetime DEFAULT current_timestamp(),
  `GhiChu` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phancong`
--

INSERT INTO `phancong` (`MaPC`, `MaDeTai`, `MaGV`, `MaCB`, `VaiTro`, `NgayPhanCong`, `GhiChu`) VALUES
(2, 2, 'GV002', 'CB001', 'Hướng dẫn', '2025-11-07 19:14:28', 'GV hướng dẫn sinh viên 002245003'),
(5, 2, 'GV003', 'CB001', 'Phản biện', '2025-11-14 00:00:00', 'GV hướng dẫn sinh viên'),
(9, 7, 'GV005', 'CB001', 'Phản biện', '2025-11-21 00:00:00', 'GV hướng dẫn sinh viên'),
(10, 5, 'GV001', NULL, 'Hướng dẫn', '2025-11-21 22:17:07', NULL),
(11, 5, 'GV006', NULL, 'Phản biện', '2025-11-21 22:17:19', NULL),
(12, 6, 'GV005', NULL, 'Hướng dẫn', '2025-11-24 22:16:13', NULL),
(13, 6, 'GV001', NULL, 'Phản biện', '2025-11-24 22:16:20', NULL),
(14, 4, 'GV001', NULL, 'Hướng dẫn', '2025-11-24 22:20:09', NULL),
(15, 4, 'GV006', NULL, 'Phản biện', '2025-11-24 22:20:23', NULL),
(16, 7, 'GV002', NULL, 'Hướng dẫn', '2025-11-24 23:21:04', NULL),
(19, 13, 'GV006', NULL, 'Hướng dẫn', '2025-11-27 19:28:15', NULL),
(20, 13, 'GV008', NULL, 'Phản biện', '2025-11-27 19:28:25', NULL),
(29, 17, 'GV006', NULL, 'Hướng dẫn', '2025-12-02 18:16:33', NULL),
(35, 40, 'GV010', NULL, 'Hướng dẫn', '2025-12-14 21:39:45', NULL),
(36, 40, 'GV002', NULL, 'Phản biện', '2025-12-14 21:39:59', NULL),
(37, 46, 'GV009', NULL, 'Hướng dẫn', '2025-12-16 22:31:48', NULL),
(38, 46, 'GV001', NULL, 'Phản biện', '2025-12-16 22:31:58', NULL),
(39, 17, 'GV001', NULL, 'Phản biện', '2025-12-16 23:04:47', NULL),
(40, 47, 'GV016', NULL, 'Hướng dẫn', '2025-12-17 11:40:07', NULL),
(41, 47, 'GV006', NULL, 'Phản biện', '2025-12-17 11:40:16', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `MaRole` bigint(20) UNSIGNED NOT NULL,
  `TenRole` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6ln5SrAfig7j30jTIKXnomOrI5DekgAnLdj0POvs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiakZhcVFUQkRaNHRlSHg4S1c1MU9DeHNSNWo3R05Ra0ZBZDRUYTJkZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1763571002),
('A5T1NDbXym7pONiTEsGCoNMZyQI5GaHdopvwXv9V', NULL, '127.0.0.1', 'Go-http-client/1.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT1NTUFE3bktsMEFjaEpNN0Z4VmhyVDRJcE80Y1ZTdGpqbVhCVDQ0WCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kZWJ1Zy1zY2hlbWEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1764153059),
('AAZIylgOpmYon5VVqm2NZa4c14AWAfNTUfCO0MYb', NULL, '127.0.0.1', 'Go-http-client/1.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiODBqaGNhd1ROMmptaVJWYXVxMFU0cEFqODVqcXIzSGtlZ1lMNGVvUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9maXgtZGItdGllbmRvIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1764153412),
('dlPvA2qhpsHfBMIoAOPlvP2XeSAkBik5b6yqAjQq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVXJBZG8zZEt1WUtMUVlzYmdiTEVRWDZUaU56cmdyeW9KVTJCbDUwbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1763909394),
('KnazDuXj20YHEBC8vy8K8CAAl9SM2KrwFRnYnMCU', 46, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZmRmaGhQV25nUlBHbm9jZGphSHZNblJoU2dOaEpud1lZV0ZveVY5bCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Npbmh2aWVuL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2luaHZpZW4vY2hhdC91bnJlYWQvY291bnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0Njt9', 1765964528),
('nHcrrWm4c4sdbtNTvTnAuojmpDTkAy8WpBD5WQie', 67, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiU0FyU01pQTNVdkdHelV3dVJjSExSQnRFWTR2S3AwTU9HVG5zU2hKRiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZ2lhbmd2aWVuL2NoYXQvdW5yZWFkL2NvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njc7fQ==', 1765964553),
('OpOsFW4oqWhZyGJ6FzbbDSdsXtcCSP8iWdVD6oXR', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN3ZFelJZYmYxNXRYeVh5RWtyYU9uWjBFVk5xOHR5ZzZ1S2ZYV01xTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1765964499),
('RSeFs2kw1KjnoMY8KLvg4AePLjlcVCIg2Itdmc6w', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNjQ3ZlQ2aG5LVG1oNnlFazRXVmtualN3amJZRU5wbW1jZld1Z1dOSCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Npbmh2aWVuL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765959519),
('RW7IakPel1n23bmDhRqJ4dHJdoW4ngGXsW3EHR09', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoianZlUEVHeWN0UWNPWk9iS2FMUW9nNEEyNWY5czR6ZWhZOHgya2x5ZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1763911048),
('S40GebKWL4aVDGEiAhlPXgGboUrtwdIgOd0cm01b', NULL, '127.0.0.1', 'Go-http-client/1.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibHRaOGVMWmxJbFF0aWtUaktoYXJ4b2lTUEU4cnM3Y0hreVZYckR5USI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kZWJ1Zy1zY2hlbWEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1764153423),
('SswLHUOyxxZj6uFNLGduyZuZ0nJKcBvdzUSnyBvp', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUWt3Q0p5MG5EbGhheFlrYkJOdDhybnZycEoxYTM5eFZFblNqQUcydSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1765943290),
('XFnzFbsdUMccEHqIAgPvVk3RAPqCwaLWOmWtnxaS', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQTVZU3c2MXVjUWpFc1VaVDN0TVVUMFFISGdMNWhBWTl3SGM2ZGlJaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1763999841),
('y5b1iF1ogqMyWsn6p2mJDDjRyQGUKK0O1uPHuPqq', NULL, '127.0.0.1', 'Go-http-client/1.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVXJsclIwa1RxTlNTbGZFUUNlQWxaa2d3bXE4ellDWnp1QlU1akgxUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kZWJ1Zy1iYW9jYW8tbGluayI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764155273);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sinhvien`
--

CREATE TABLE `sinhvien` (
  `MaSV` varchar(20) NOT NULL,
  `TenSV` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `GioiTinh` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NgaySinh` datetime DEFAULT NULL,
  `MaCCCD` varchar(15) DEFAULT NULL,
  `TonGiao` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `Email` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NoiSinh` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `HKTT` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `DanToc` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `BacDaoTao` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `MaKhoa` int(11) DEFAULT NULL,
  `MaNganh` int(11) DEFAULT NULL,
  `MaLop` int(11) DEFAULT NULL,
  `MaNamHoc` int(11) DEFAULT NULL,
  `MaTK` int(11) DEFAULT NULL,
  `TrangThai` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sinhvien`
--

INSERT INTO `sinhvien` (`MaSV`, `TenSV`, `GioiTinh`, `NgaySinh`, `MaCCCD`, `TonGiao`, `SDT`, `Email`, `NoiSinh`, `HKTT`, `DanToc`, `BacDaoTao`, `MaKhoa`, `MaNganh`, `MaLop`, `MaNamHoc`, `MaTK`, `TrangThai`, `created_at`, `updated_at`, `HinhAnh`) VALUES
('0022223001', 'Lê Hoàng Trọng Nghĩa', 'Nam', '2004-02-14 00:00:00', '089123459789', NULL, '0329694987', 'nghia@gmail.com', NULL, 'Đồng Tháp', NULL, NULL, 1, 3, 8, 1, 84, 'Đang học', '2025-12-03 13:19:40', '2025-12-03 13:19:40', NULL),
('0022425001', 'Nguyễn Hoàng Mẫn', 'Nam', '2004-05-15 00:00:00', '088894561561', 'Không', '0321469874', 'hman@gmail.com', 'Đồng Tháp', 'Đồng Tháp', 'Kinh', 'Đại học', 1, 2, 1, 1, 12, 'Đang học', '2025-11-14 02:30:39', '2025-12-16 07:30:33', 'img/uploads/images/1764327891_126.jpg'),
('0022425002', 'Trần Thị Bình An', 'Nữ', '2000-02-14 00:00:00', NULL, 'Không', '0321456987', 'binhan@gmail.com', NULL, NULL, 'Kinh', NULL, 1, 2, 1, 1, 16, 'Đang học', '2025-11-19 12:18:35', '2025-11-19 12:18:36', NULL),
('0022425003', 'Nguyễn Minh', 'Nam', '2000-03-12 00:00:00', NULL, 'Không', '0369852147', 'minh@gmail.com', NULL, 'Đồng Tháp', 'Kinh', NULL, 1, 2, 1, 1, 17, 'Đang học', '2025-11-19 12:26:43', '2025-11-19 12:50:36', NULL),
('0022425004', 'Đoàn Văn Hậu', 'Nam', '1999-04-12 00:00:00', '089128456789', NULL, '0321694987', 'vhau@gmail.com', NULL, NULL, NULL, NULL, 1, 3, 1, 1, 20, 'Tốt nghiệp', '2025-11-21 04:56:01', '2025-12-17 03:59:10', NULL),
('0022425005', 'Nguyễn Minh Thư', 'Nữ', '2004-02-11 00:00:00', NULL, 'Không', '0921654987', 'minhthu@gmail.com', NULL, NULL, 'Kinh', NULL, 1, 3, 1, 1, 21, 'Đang học', '2025-11-21 05:13:19', '2025-11-21 05:13:19', NULL),
('0022425006', 'Lê Hoàng Anh Khoa', 'Nam', '2000-11-11 00:00:00', '087456989525', NULL, '0321654988', 'khoa@gmail.com', NULL, NULL, NULL, NULL, 1, 6, 2, 1, 32, 'Đang học', '2025-11-28 05:59:21', '2025-11-28 05:59:22', NULL),
('002245001', 'Nguyễn Văn An', 'Nam', '2003-05-01 00:00:00', '079444555666', NULL, '0901234001', 'a.sv@uni.edu.vn', NULL, NULL, NULL, NULL, 1, 2, 2, 1, 6, 'Đang học', NULL, '2025-11-10 07:04:40', NULL),
('002245003', 'Lê Hoàng Anh', 'Nam', '2003-02-22 00:00:00', '079888777666', NULL, '0901234003', 'c.sv@uni.edu.vn', NULL, NULL, NULL, NULL, 1, 3, 2, 1, 8, 'Đang học', NULL, '2025-11-10 07:05:04', NULL),
('002245004', 'Phạm Mỹ Dung', 'Nữ', '2004-02-15 00:00:00', '079555444333', 'Không', '0901234004', 'd.sv@uni.edu.vn', 'Đồng Tháp', 'Đồng Tháp', NULL, NULL, 1, 3, 2, 1, 9, 'Đang học', NULL, '2025-12-01 15:23:26', NULL),
('002245005', 'Hoàng Văn Nghĩa', 'Nam', '2003-08-20 00:00:00', '079999888777', NULL, '0901234005', 'e.sv@uni.edu.vn', NULL, NULL, NULL, NULL, 1, 2, 2, 1, 10, 'Đang học', NULL, '2025-11-10 07:05:26', NULL),
('002245006', 'Nguyễn Văn An', 'Nam', '1970-01-01 00:00:00', NULL, NULL, '0912345678', 'vana@example.com', NULL, NULL, NULL, NULL, 1, 1, 3, 1, 24, 'Đang học', '2025-11-25 05:56:03', '2025-11-28 06:00:56', NULL),
('002245007', 'Trần Thị Bình Minh', 'Nữ', '1970-01-01 00:00:00', '087456932158', 'Không', '0987654321', 'thib@example.com', 'Đồng Tháp', NULL, 'Kinh', NULL, 1, 3, 3, 1, 25, 'Đang học', '2025-11-25 05:56:04', '2025-11-28 06:00:57', 'img/uploads/images/1764161120_127.jpg'),
('002245009', 'Nguyễn Văn Hòa', 'Nam', '2003-02-15 00:00:00', NULL, NULL, '912345679', 'vana@example.com', NULL, NULL, NULL, NULL, 1, 1, 3, 1, 34, 'Đang học', '2025-11-28 06:17:39', '2025-11-28 06:17:40', NULL),
('002245010', 'Trần Thị Bình Minh', 'Nữ', '2004-05-25 00:00:00', NULL, NULL, '987654328', 'thib@example.com', NULL, NULL, NULL, NULL, 1, 3, 4, 1, 35, 'Đang học', '2025-11-28 06:17:40', '2025-11-28 06:17:41', NULL),
('002245012', 'Nguyễn Minh Mẫn', 'Nam', '2004-11-03 00:00:00', NULL, 'Không', '0329684987', 'man@gmail.com', NULL, NULL, 'Kinh', NULL, 1, 3, 6, 1, 26, 'Đang học', '2025-11-25 05:38:50', '2025-11-25 05:38:50', NULL),
('002245013', 'Lê Thị Thu Thảo', 'Nữ', '2005-01-08 00:00:00', NULL, NULL, '901234567', 'thuthao@example.edu', NULL, NULL, NULL, NULL, 1, 1, 5, 1, 39, 'Đang học', '2025-11-28 12:16:55', '2025-11-28 12:16:56', NULL),
('002245014', 'Phạm Minh Quân', 'Nam', '2005-12-03 00:00:00', NULL, NULL, '902345678', 'minhhquan@example.edu', NULL, NULL, NULL, NULL, 1, 2, 5, 1, 40, 'Đang học', '2025-11-28 12:16:56', '2025-11-28 12:16:57', NULL),
('002245015', 'Trần Quang Huy', 'Nam', '1970-01-01 00:00:00', NULL, NULL, '903456789', 'quanghuy@example.edu', NULL, NULL, NULL, NULL, 4, 6, 5, 1, 41, 'Đang học', '2025-11-28 12:16:57', '2025-11-28 12:16:57', NULL),
('002245016', 'Ngô Phương Anh', 'Nữ', '2005-05-04 00:00:00', NULL, NULL, '904567890', 'phuonganh@example.edu', NULL, NULL, NULL, NULL, 5, 3, 6, 1, 42, 'Đang học', '2025-11-28 12:16:57', '2025-11-28 12:16:58', NULL),
('002245017', 'Hoàng Văn Nam', 'Nam', '1970-01-01 00:00:00', '089123455789', NULL, '0905678901', 'vannam@example.edu', NULL, NULL, NULL, NULL, 1, 4, 6, 1, 43, 'Nghỉ luôn', '2025-11-28 12:16:58', '2025-12-17 03:59:35', NULL),
('002245018', 'Đặng Thị Bích Ngọc', 'Nữ', '1970-01-01 00:00:00', NULL, NULL, '906789012', 'bichngoc@example.edu', NULL, NULL, NULL, NULL, 5, 5, 6, 1, 44, 'Đang học', '2025-11-28 12:16:58', '2025-11-28 12:16:59', NULL),
('002245019', 'Vũ Đức Thắng', 'Nam', '1970-01-01 00:00:00', NULL, NULL, '907890123', 'ducthang@example.edu', NULL, NULL, NULL, NULL, 7, 1, 6, 1, 45, 'Đang học', '2025-11-28 12:16:59', '2025-11-28 12:17:00', NULL),
('002245020', 'Nguyễn Tuấn Kiệt', 'Nam', '2004-02-18 00:00:00', '087456989524', 'Không', '0908901234', 'tuankiet@example.edu', 'Đồng Tháp', NULL, 'Kinh', NULL, 3, 2, 7, 1, 46, 'Đang học', '2025-11-28 12:17:00', '2025-11-29 16:33:56', NULL),
('002245021', 'Mai Hồng Liên', 'Nữ', '2005-08-07 00:00:00', NULL, NULL, '909012345', 'honglien@example.edu', NULL, NULL, NULL, NULL, 2, 6, 7, 1, 47, 'Đang học', '2025-11-28 12:17:00', '2025-11-28 12:17:01', NULL),
('002245023', 'TRẦN THỊ VÂN ANH', 'Nữ', '2000-01-01 00:00:00', '087653982134', 'Không', '0901234563', 'vanh@example.edu', 'Đồng Tháp', NULL, 'Kinh', NULL, 1, 1, 1, 1, 72, 'Đang học', '2025-11-29 14:21:57', '2025-12-17 03:59:52', 'img/uploads/images/1765902951_112.jpg'),
('002245025', 'CHÂU TIẾN THÀNH', 'Nam', '1970-01-01 00:00:00', NULL, NULL, '0909234568', 'tienthanh@example.edu', NULL, NULL, NULL, NULL, 4, 6, 2, 1, 74, 'Đang học', '2025-11-29 14:21:58', '2025-11-29 14:21:58', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `MaTK` int(11) NOT NULL,
  `MaSo` varchar(20) NOT NULL,
  `MatKhau` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `VaiTro` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `TrangThai` varchar(50) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`MaTK`, `MaSo`, `MatKhau`, `VaiTro`, `TrangThai`) VALUES
(1, 'admin', '123456', 'Admin', 'active'),
(2, 'CB001', '$2y$12$S5hXZuwOVpJQarl/iOoHsuII2nHWdPhLxnch0wwj/djj5v5OnzHv6', 'CanBo', 'active'),
(3, 'GV001', '123456', 'GiangVien', 'active'),
(4, 'GV002', '123456', 'GiangVien', 'active'),
(5, 'GV003', '123456', 'GiangVien', 'active'),
(6, '002245001', '123456', 'SinhVien', 'active'),
(8, '002245003', '123456', 'SinhVien', 'active'),
(9, '002245004', '123456', 'SinhVien', 'active'),
(10, '002245005', '123456', 'SinhVien', 'active'),
(12, '0022425001', '$2y$12$r27hZNE2KOFwKuinJWFSsukZqZzX.o/MUo4/6NA/XZPP7he1QNDZq', 'SinhVien', 'locked'),
(14, 'GV004', '$2y$12$GfCJHJB9oIBRJjrt3cj/UeKdhh2fPglKzT1L7wd9eIv7Yn.S01u9O', 'GiangVien', 'active'),
(15, 'GV005', '$2y$12$CFkpLtlz04dIfD9Q3/Na8eO2001tEZHOcn9sQIyTbQ2mbkZcQbdKG', 'GiangVien', 'active'),
(16, '0022425002', '$2y$12$Nt2L8db5TmYxrh.FLg.FYuXa9VavR2jz.06yfWHSrJpiX2gvhWKZu', 'SinhVien', 'active'),
(17, '0022425003', '$2y$12$hwj/ZFnnCQCKPFVnWds/UePTkaoemLJahUmREAxtaypIJiSF26MV.', 'SinhVien', 'active'),
(18, 'GV006', '$2y$12$6F0f9iahAbnJFIgtZ0gyouyxfQwLecZNzk7UYtdvHCEaUHww5ailK', 'GiangVien', 'active'),
(19, 'GV007', '$2y$12$g2Zhc0FLuEInRcn2cg7PgeBKykj77Btu4OLBFDTJohmCIIMiOQhyi', 'giangvien', 'active'),
(20, '0022425004', '$2y$12$gqbTytNr1nVvDgVSMLgF2O4h9e6xAszni5o9QSJ73nSdqOpbL9rSa', 'SinhVien', 'active'),
(21, '0022425005', '$2y$12$gOJwgsfXGAgtRsqCVKaAZu0CSNXKs/upUKn/fhHUH3xdO1Mea44O2', 'SinhVien', 'active'),
(23, 'GV008', '$2y$12$MMEtzJLOE41VbzK2Of96yeE5IOvLpEziMHq7xj94UZUoWtSNzo9o6', 'GiangVien', 'active'),
(24, '002245006', '$2y$12$nklZ8LkRYE8y.0lIwlVqTOdUucgD9VSPlllmr9.U9ZxzdkXaP553O', 'SinhVien', 'active'),
(25, '002245007', '$2y$12$l9935jsWw0cKZgfoXd5WkOqZQcRnkZ6FRQpzdrNHjFJ8mtz4PjnD6', 'SinhVien', 'active'),
(26, '002245012', '$2y$12$a9FMmyg2Ol5wM5E8Cy/YXeLLfjgIYh3WCmMGWqDETPGVSEztpi.Wa', 'SinhVien', 'active'),
(29, 'GV009', '$2y$12$KyCRNAcXYFJxIygWswszOuEuF6NyY4oCM2u9aXocAzV8Kl0jC.4P2', 'GiangVien', 'active'),
(30, 'GV010', '$2y$12$8vGo7J8hCKLzXH1SvKmgCem8rYNMHdFXXxJOglYHm2orD/kZcUTFu', 'GiangVien', 'active'),
(31, 'GV011', '$2y$12$uNKw9DQ.VLNUj8mA2QnEcOiCjBQcurZh9.WraV9FgFQEQr7d0YkZm', 'GiangVien', 'active'),
(32, '0022425006', '$2y$12$iKsXFg/JkVjt9g2lz5oMVOxNPA6JLRA0j/e74mWxxlFkTwryxGI1e', 'SinhVien', 'active'),
(34, '002245009', '$2y$12$5mgIHzObbhfZobJ6H4SRpulN3xMO3SzY1CIQSZOy70spTo2DGQK7G', 'SinhVien', 'active'),
(35, '002245010', '$2y$12$Y0g5DE38fomYuTGdPvcUxe4o9bUNfRVwvSAz9X4s3RL0i1kMAUT4m', 'SinhVien', 'active'),
(38, 'CB002', '$2y$12$9LQ5cFZhx/375h8k6E6AVu3VYYL0Cm0mGS7cbkOapN7L9d/fEe8pa', 'CanBo', 'active'),
(39, '002245013', '$2y$12$vZFgyrjbk/kZf3vEaV2cKushSer2v0qVSQyYh54NZCS9KxvicddgO', 'SinhVien', 'active'),
(40, '002245014', '$2y$12$ETXi22bwLkbJ/Xa6Ko2uLu1oBVNklvHjc1fFGLLU7J7KZjrsyVMDW', 'SinhVien', 'active'),
(41, '002245015', '$2y$12$wRG4XJLRCdej6qxXTjeZWu1PHgTycasLAQbOcJWcGBsIpv25j2dny', 'SinhVien', 'active'),
(42, '002245016', '$2y$12$6R/vjeho.w.DyCRK2cvFh.g/iYNrQyftUp.noBtpH79FOJ5nx/UCG', 'SinhVien', 'active'),
(43, '002245017', '$2y$12$VLVBigjz6wlBUxkNGSMbPuccVgK31uukaEDJDIMXj2Vl9Ei3ByUbS', 'SinhVien', 'active'),
(44, '002245018', '$2y$12$4K4oOHJ8MVWG5wLICMMXBOcKkcsQPE8sj3P6zVhztAYJ90g6Pfu9i', 'SinhVien', 'active'),
(45, '002245019', '$2y$12$W3GFaEWs8YKu2sUpF8Yb1eshIgX112Ovijvte9jPR5UJK7kuSK8n6', 'SinhVien', 'active'),
(46, '002245020', '$2y$12$beZrZ0pCDFoVDntStBwge.kgtwn9sNNh640lFcmvUnp8rYrw/eD4S', 'SinhVien', 'active'),
(47, '002245021', '$2y$12$BWCvUQPPp1k7Cya5V058CekV6Gbs93pkCzhYS5MI5BP27RogFzzPO', 'SinhVien', 'active'),
(59, 'GV012', '$2y$12$RXew3aFSMxo10Gg/3R5wmu.DkYyKQx0mnXLuiexRWjIzaVdRoEx1S', 'GiangVien', 'active'),
(60, 'GV013', '$2y$12$n1y0o6EerOP7Qr6y2gu89OpGxlT9zy8Q7f9wPDLzATNgl4OITibPm', 'GiangVien', 'active'),
(61, 'GV014', '$2y$12$4UlFuqfe8EGmzf0gHwxQ3etMUzb/YiviBTloRPL72MT/oavSyTzvO', 'GiangVien', 'active'),
(62, 'GV015', '$2y$12$eTzNu2/YUzbNFIygqnSU1Ov6zngxIrWxblzNL2zMkx4j2TUSvW0K2', 'GiangVien', 'active'),
(63, 'GV016', '$2y$12$XBpS9AxJCOX0fbOhFpgwKetpHLKrRHm8tKuELU4sL/0AD6T8dJlgG', 'GiangVien', 'active'),
(64, 'GV017', '$2y$12$jC1QqlZITGMcjSQaWvclpO0QnxoaCiMiLOI6.ikuDXgZkSYyfHpTm', 'GiangVien', 'active'),
(66, 'GV019', '$2y$12$MstpvrewgNEs8dTQ1UDxCOuwKOLENmlEoZ6r4aOktwOc7f5NKbLEG', 'GiangVien', 'active'),
(67, 'GV020', '$2y$12$XlycseGZNiiZ9xWwucC7ouluFWZw34nd7bOADNcAeeYQ/jbITE90O', 'GiangVien', 'active'),
(68, 'GV021', '$2y$12$2oHSHNs.imJjm96D3OjTYO3rPejmPJr7FVwHxfcURLMKo.sGoF6rO', 'GiangVien', 'active'),
(69, 'GV022', '$2y$12$2RqCal46Y..RvgpYJiiB9ubt.eDHIv1EB2/UOAbNmuaDz.R9ZDt26', 'GiangVien', 'active'),
(70, 'GV023', '$2y$12$63Io7MxRdKX2ZhkQDPHPnePrAcfX1C2/b.mL6i58MlDnqC6yd68Hm', 'GiangVien', 'active'),
(71, 'GV024', '$2y$12$L4Q6JQQVs8Hrmt0AiZhZfO3HKgCL80xmyuzAaZp4MeVgWZ78GgIVG', 'GiangVien', 'active'),
(72, '002245023', '$2y$12$GBVmtAcm3wvnemfmwYpnb.Lfl3qamnKA6liHsjU1lKm3VzpTVRdsC', 'SinhVien', 'active'),
(74, '002245025', '$2y$12$XG52Phgd17MW6wXCw7h.0.WYhJ80qhxt0k3xzhN8vNfRxQaAGtQDC', 'SinhVien', 'active'),
(75, 'CB003', '$2y$12$mLkK2lign6jm8DAZs6DLguJs2gCIUvTRwAUNtZ.ISpn7HZpwUmsum', 'CanBo', 'locked'),
(77, 'GV025', '$2y$12$TZoNZ4ADUjxWjT8S.DarteqybQn51pXn4eDsFMIr71.YRtUQS90s.', 'GiangVien', 'active'),
(83, 'GV026', '$2y$12$5u0iVq4c7TWd0oszYEeZd.fjsd6OsZaZURkpjHxsxVradevcTuuni', 'GiangVien', 'active'),
(84, '0022223001', '$2y$12$L/odHt963BuHDZsZJbG1keAxmjw/3VlzaeGwS9ZTQCKBndwvSJ2NW', 'SinhVien', 'active'),
(88, 'GV027', '$2y$12$90iem.EiMkLGgkSsGqUDqelSkxrJ8HvctvqwvkPIQKwv3skwdoDeq', 'GiangVien', 'active'),
(99, 'CB004', '$2y$12$DTckKCv4ZIiHe8JobRRNwOof4KhzKXDoAanY233OoCNVXNDH5tNni', 'CanBo', 'active');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thoigiandangky`
--

CREATE TABLE `thoigiandangky` (
  `MaTG` int(11) NOT NULL,
  `NgayMo` datetime NOT NULL,
  `NgayDong` datetime NOT NULL,
  `ChoPhepDoi` bit(1) DEFAULT b'0',
  `MoTa` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thoigiandangky`
--

INSERT INTO `thoigiandangky` (`MaTG`, `NgayMo`, `NgayDong`, `ChoPhepDoi`, `MoTa`) VALUES
(1, '2025-11-07 00:00:00', '2025-11-08 23:59:59', b'1', 'Mở đăng ký và cho phép đổi đề tài trong 2 ngày'),
(2, '2025-12-01 00:00:00', '2025-12-02 23:59:59', b'0', 'Đợt đăng ký bổ sung');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongbao`
--

CREATE TABLE `thongbao` (
  `MaTB` int(11) NOT NULL,
  `NoiDung` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `TGDang` datetime DEFAULT NULL,
  `MaCB` varchar(20) DEFAULT NULL,
  `TenFile` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `DoiTuongNhan` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `MucDo` varchar(20) DEFAULT 'info',
  `MaNguoiNhan` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thongbao`
--

INSERT INTO `thongbao` (`MaTB`, `NoiDung`, `TGDang`, `MaCB`, `TenFile`, `DoiTuongNhan`, `MucDo`, `MaNguoiNhan`) VALUES
(1, 'Mở đăng ký đề tài niên luận từ 07-08/11/2025', '2025-11-05 00:00:00', 'CB001', NULL, 'SV', 'Khan', NULL),
(4, 'Hoàn thành và nộp đề tài vào ngày 4/12/2025', '2025-11-19 17:56:56', 'CB001', '1763575015_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'TatCa', 'Khan', NULL),
(8, 'Thời gian hoàn thành đồ án là từ ngày 1/10/2025 đến ngày 1/12/2025', '2025-11-19 18:13:19', 'CB001', NULL, 'TatCa', 'QuanTrong', NULL),
(11, 'Giảng viên tiến hành cập nhật đề tài cho sinh viên đăng lý', '2025-11-27 11:32:49', 'CB001', NULL, 'GV', 'QuanTrong', NULL),
(12, 'Mật khẩu của bạn đã được Admin reset lại. Mật khẩu mới: 123456. Vui lòng đổi mật khẩu sau khi đăng nhập.', '2025-11-27 12:35:45', NULL, NULL, 'GV', 'QuanTrong', 'GV007'),
(13, 'Điểm đã được công bố', '2025-11-27 23:26:16', 'CB001', NULL, 'SV', 'Khan', NULL),
(23, 'fgjnhvnvh', '2025-12-17 11:20:43', 'CB001', NULL, 'GV', 'QuanTrong', NULL),
(25, 'thông báo ra đề tài đồ án 2', '2025-12-17 16:34:07', 'CB001', NULL, 'GV', 'QuanTrong', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tiendo`
--

CREATE TABLE `tiendo` (
  `MaTienDo` int(11) NOT NULL,
  `MaDeTai` int(11) DEFAULT NULL,
  `NoiDung` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ThoiGianCapNhat` datetime DEFAULT NULL,
  `TrangThai` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `GhiChu` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Deadline` datetime DEFAULT NULL,
  `NgayNop` datetime DEFAULT NULL,
  `LinkFile` varchar(255) DEFAULT NULL,
  `TenFile` varchar(255) DEFAULT NULL,
  `FileCodeID` int(11) DEFAULT NULL,
  `Diem` float DEFAULT NULL,
  `NhanXet` text DEFAULT NULL,
  `NgayCham` datetime DEFAULT NULL,
  `MaGV` varchar(20) DEFAULT NULL,
  `HanNop` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tiendo`
--

INSERT INTO `tiendo` (`MaTienDo`, `MaDeTai`, `NoiDung`, `ThoiGianCapNhat`, `TrangThai`, `GhiChu`, `Deadline`, `NgayNop`, `LinkFile`, `TenFile`, `FileCodeID`, `Diem`, `NhanXet`, `NgayCham`, `MaGV`, `HanNop`) VALUES
(3, 2, 'Tạo giao diện sản phẩm', '2025-09-30 00:00:00', 'Đang thực hiện', 'Đang thêm giỏ hàng', '2025-11-06 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 2, 'Website bán hàng trang sức', '2025-11-24 15:47:14', 'Trễ hạn', NULL, '2025-11-18 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 7, 'sahscs', '2025-11-24 15:47:42', 'Đang thực hiện', 'ghvh', '2025-11-12 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 7, 'chh', '2025-11-24 15:51:25', 'Trễ hạn', NULL, '2025-11-23 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 7, 'cxvvxcvfdvfd', '2025-11-24 15:54:11', 'Trễ hạn', NULL, '2025-10-30 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 13, 'nộp code demo', '2025-11-27 11:52:31', 'Được nộp bổ sung', NULL, '2025-11-10 00:00:00', '2025-11-27 12:19:42', NULL, NULL, 25, 9, NULL, '2025-12-13 20:06:04', 'GV006', NULL),
(22, 40, 'nộp chương 1', '2025-12-13 21:49:34', 'Chưa nộp', NULL, '2025-12-16 00:00:00', '2025-12-14 21:34:59', 'storage/baocao/1765722899_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', NULL, NULL, NULL, NULL, NULL, NULL),
(24, 40, 'nộp  ma nguon', '2025-12-14 21:35:57', 'Chưa nộp', NULL, '2025-12-26 00:00:00', '2025-12-14 21:36:29', NULL, NULL, 78, NULL, NULL, NULL, NULL, NULL),
(25, 46, 'nop chuong 1', '2025-12-16 21:54:32', 'Chưa nộp', NULL, '2025-12-17 00:00:00', '2025-12-16 22:26:10', 'storage/baocao/1765898770_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', '20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', NULL, 8.5, NULL, '2025-12-16 22:28:36', 'GV009', NULL),
(26, 46, 'nop code', '2025-12-16 22:29:01', 'Chưa nộp', NULL, '2025-12-17 00:00:00', '2025-12-16 22:30:17', NULL, NULL, 90, 9, NULL, '2025-12-16 22:30:38', 'GV009', NULL),
(27, 17, 'nop c 1', '2025-12-16 22:55:57', 'Được nộp bổ sung', NULL, '2025-12-15 00:00:00', '2025-12-16 23:02:25', 'storage/baocao/1765900945_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', '20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', NULL, 9, NULL, '2025-12-16 23:02:39', 'GV006', NULL),
(28, 17, 'code', '2025-12-16 23:02:53', 'Chưa nộp', NULL, '2025-12-17 00:00:00', '2025-12-16 23:03:08', NULL, NULL, 94, 8.5, NULL, '2025-12-16 23:03:24', 'GV006', NULL),
(29, 47, 'N BC C1', '2025-12-17 11:31:11', 'Chưa nộp', NULL, '2025-12-23 00:00:00', '2025-12-17 11:31:43', 'storage/baocao/1765945903_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', '20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', NULL, 9, 'TỐT', '2025-12-17 11:32:04', 'GV016', NULL),
(30, 47, 'HJHJGD', '2025-12-17 11:32:44', 'Chưa nộp', NULL, '2025-12-19 00:00:00', '2025-12-17 11:37:47', 'storage/baocao/1765946267_TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', 'TÍNH CÁC ĐẶC TRƯNG CỦA MẪU.pdf', NULL, 8.5, NULL, '2025-12-17 11:38:14', 'GV016', NULL),
(31, 47, 'GVBNVGN', '2025-12-17 11:32:54', 'Chưa nộp', NULL, '2025-12-28 00:00:00', '2025-12-17 11:37:57', NULL, NULL, 104, 8, NULL, '2025-12-17 11:38:19', 'GV016', NULL),
(32, 50, 'gnjgn', '2025-12-17 16:36:58', 'Chưa nộp', NULL, '2025-12-18 00:00:00', '2025-12-17 16:38:07', 'storage/baocao/1765964287_20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', '20232249412LY_LICH_SINH_VIEN_2021_DAI_HOC_DONG_THAP.pdf', NULL, 8, NULL, '2025-12-17 16:38:48', 'GV020', NULL),
(33, 50, 'ffgfg', '2025-12-17 16:39:13', 'Chưa nộp', NULL, '2025-12-25 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_lock` tinyint(1) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `baocao`
--
ALTER TABLE `baocao`
  ADD PRIMARY KEY (`MaBC`),
  ADD KEY `MaDeTai` (`MaDeTai`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `canboql`
--
ALTER TABLE `canboql`
  ADD PRIMARY KEY (`MaCB`),
  ADD KEY `MaCCCD` (`MaCCCD`),
  ADD KEY `MaTK` (`MaTK`),
  ADD KEY `fk_canbo_khoa` (`MaKhoa`);

--
-- Chỉ mục cho bảng `cauhinhhethong`
--
ALTER TABLE `cauhinhhethong`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cccd`
--
ALTER TABLE `cccd`
  ADD PRIMARY KEY (`MaCCCD`);

--
-- Chỉ mục cho bảng `chamdiem`
--
ALTER TABLE `chamdiem`
  ADD PRIMARY KEY (`MaCham`),
  ADD KEY `MaDeTai` (`MaDeTai`),
  ADD KEY `MaGV` (`MaGV`),
  ADD KEY `idx_matiendo` (`MaTienDo`);

--
-- Chỉ mục cho bảng `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_conversation_giangvien` (`MaGV`),
  ADD KEY `fk_conversation_detai` (`MaDeTai`),
  ADD KEY `fk_conversation_sinhvien` (`MaSV`);

--
-- Chỉ mục cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_message_conversation` (`conversation_id`);

--
-- Chỉ mục cho bảng `detai`
--
ALTER TABLE `detai`
  ADD PRIMARY KEY (`MaDeTai`),
  ADD KEY `MaGV` (`MaGV`),
  ADD KEY `MaCB` (`MaCB`),
  ADD KEY `MaNamHoc` (`MaNamHoc`);

--
-- Chỉ mục cho bảng `detai_sinhvien`
--
ALTER TABLE `detai_sinhvien`
  ADD PRIMARY KEY (`MaDeTai`,`MaSV`),
  ADD UNIQUE KEY `MaSV` (`MaSV`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_type_index` (`type`),
  ADD KEY `files_extension_index` (`extension`),
  ADD KEY `files_is_deleted_index` (`is_deleted`),
  ADD KEY `files_name_index` (`name`);

--
-- Chỉ mục cho bảng `giangvien`
--
ALTER TABLE `giangvien`
  ADD PRIMARY KEY (`MaGV`),
  ADD KEY `MaKhoa` (`MaKhoa`),
  ADD KEY `MaCCCD` (`MaCCCD`),
  ADD KEY `MaTK` (`MaTK`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `khoa`
--
ALTER TABLE `khoa`
  ADD PRIMARY KEY (`MaKhoa`);

--
-- Chỉ mục cho bảng `lop`
--
ALTER TABLE `lop`
  ADD PRIMARY KEY (`MaLop`),
  ADD KEY `MaKhoa` (`MaKhoa`),
  ADD KEY `MaNganh` (`MaNganh`),
  ADD KEY `MaNamHoc` (`MaNamHoc`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `namhoc`
--
ALTER TABLE `namhoc`
  ADD PRIMARY KEY (`MaNamHoc`);

--
-- Chỉ mục cho bảng `nganh`
--
ALTER TABLE `nganh`
  ADD PRIMARY KEY (`MaNganh`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `phancong`
--
ALTER TABLE `phancong`
  ADD PRIMARY KEY (`MaPC`),
  ADD KEY `MaDeTai` (`MaDeTai`),
  ADD KEY `MaGV` (`MaGV`),
  ADD KEY `MaCB` (`MaCB`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`MaRole`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD PRIMARY KEY (`MaSV`),
  ADD KEY `MaLop` (`MaLop`),
  ADD KEY `MaKhoa` (`MaKhoa`),
  ADD KEY `MaNganh` (`MaNganh`),
  ADD KEY `MaCCCD` (`MaCCCD`),
  ADD KEY `MaTK` (`MaTK`),
  ADD KEY `MaNamHoc` (`MaNamHoc`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`MaTK`);

--
-- Chỉ mục cho bảng `thoigiandangky`
--
ALTER TABLE `thoigiandangky`
  ADD PRIMARY KEY (`MaTG`);

--
-- Chỉ mục cho bảng `thongbao`
--
ALTER TABLE `thongbao`
  ADD PRIMARY KEY (`MaTB`),
  ADD KEY `MaCB` (`MaCB`);

--
-- Chỉ mục cho bảng `tiendo`
--
ALTER TABLE `tiendo`
  ADD PRIMARY KEY (`MaTienDo`),
  ADD KEY `MaDeTai` (`MaDeTai`),
  ADD KEY `MaGV` (`MaGV`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `baocao`
--
ALTER TABLE `baocao`
  MODIFY `MaBC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `cauhinhhethong`
--
ALTER TABLE `cauhinhhethong`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `chamdiem`
--
ALTER TABLE `chamdiem`
  MODIFY `MaCham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT cho bảng `chat_conversations`
--
ALTER TABLE `chat_conversations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `detai`
--
ALTER TABLE `detai`
  MODIFY `MaDeTai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT cho bảng `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khoa`
--
ALTER TABLE `khoa`
  MODIFY `MaKhoa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `lop`
--
ALTER TABLE `lop`
  MODIFY `MaLop` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `namhoc`
--
ALTER TABLE `namhoc`
  MODIFY `MaNamHoc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `nganh`
--
ALTER TABLE `nganh`
  MODIFY `MaNganh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `phancong`
--
ALTER TABLE `phancong`
  MODIFY `MaPC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `MaRole` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `MaTK` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT cho bảng `thoigiandangky`
--
ALTER TABLE `thoigiandangky`
  MODIFY `MaTG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `thongbao`
--
ALTER TABLE `thongbao`
  MODIFY `MaTB` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `tiendo`
--
ALTER TABLE `tiendo`
  MODIFY `MaTienDo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `baocao`
--
ALTER TABLE `baocao`
  ADD CONSTRAINT `baocao_ibfk_1` FOREIGN KEY (`MaDeTai`) REFERENCES `detai` (`MaDeTai`);

--
-- Các ràng buộc cho bảng `canboql`
--
ALTER TABLE `canboql`
  ADD CONSTRAINT `canboql_ibfk_1` FOREIGN KEY (`MaCCCD`) REFERENCES `cccd` (`MaCCCD`),
  ADD CONSTRAINT `canboql_ibfk_2` FOREIGN KEY (`MaTK`) REFERENCES `taikhoan` (`MaTK`),
  ADD CONSTRAINT `fk_canbo_khoa` FOREIGN KEY (`MaKhoa`) REFERENCES `khoa` (`MaKhoa`);

--
-- Các ràng buộc cho bảng `chamdiem`
--
ALTER TABLE `chamdiem`
  ADD CONSTRAINT `chamdiem_ibfk_1` FOREIGN KEY (`MaDeTai`) REFERENCES `detai` (`MaDeTai`),
  ADD CONSTRAINT `chamdiem_ibfk_2` FOREIGN KEY (`MaGV`) REFERENCES `giangvien` (`MaGV`);

--
-- Các ràng buộc cho bảng `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD CONSTRAINT `fk_conversation_detai` FOREIGN KEY (`MaDeTai`) REFERENCES `detai` (`MaDeTai`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_conversation_giangvien` FOREIGN KEY (`MaGV`) REFERENCES `giangvien` (`MaGV`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_conversation_sinhvien` FOREIGN KEY (`MaSV`) REFERENCES `sinhvien` (`MaSV`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `fk_message_conversation` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `detai`
--
ALTER TABLE `detai`
  ADD CONSTRAINT `detai_ibfk_1` FOREIGN KEY (`MaGV`) REFERENCES `giangvien` (`MaGV`),
  ADD CONSTRAINT `detai_ibfk_2` FOREIGN KEY (`MaCB`) REFERENCES `canboql` (`MaCB`),
  ADD CONSTRAINT `detai_ibfk_3` FOREIGN KEY (`MaNamHoc`) REFERENCES `namhoc` (`MaNamHoc`);

--
-- Các ràng buộc cho bảng `detai_sinhvien`
--
ALTER TABLE `detai_sinhvien`
  ADD CONSTRAINT `detai_sinhvien_ibfk_1` FOREIGN KEY (`MaDeTai`) REFERENCES `detai` (`MaDeTai`) ON DELETE CASCADE,
  ADD CONSTRAINT `detai_sinhvien_ibfk_2` FOREIGN KEY (`MaSV`) REFERENCES `sinhvien` (`MaSV`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `giangvien`
--
ALTER TABLE `giangvien`
  ADD CONSTRAINT `giangvien_ibfk_1` FOREIGN KEY (`MaKhoa`) REFERENCES `khoa` (`MaKhoa`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `giangvien_ibfk_2` FOREIGN KEY (`MaCCCD`) REFERENCES `cccd` (`MaCCCD`),
  ADD CONSTRAINT `giangvien_ibfk_3` FOREIGN KEY (`MaTK`) REFERENCES `taikhoan` (`MaTK`);

--
-- Các ràng buộc cho bảng `lop`
--
ALTER TABLE `lop`
  ADD CONSTRAINT `lop_ibfk_1` FOREIGN KEY (`MaKhoa`) REFERENCES `khoa` (`MaKhoa`),
  ADD CONSTRAINT `lop_ibfk_2` FOREIGN KEY (`MaNganh`) REFERENCES `nganh` (`MaNganh`),
  ADD CONSTRAINT `lop_ibfk_3` FOREIGN KEY (`MaNamHoc`) REFERENCES `namhoc` (`MaNamHoc`);

--
-- Các ràng buộc cho bảng `phancong`
--
ALTER TABLE `phancong`
  ADD CONSTRAINT `phancong_ibfk_1` FOREIGN KEY (`MaDeTai`) REFERENCES `detai` (`MaDeTai`) ON DELETE CASCADE,
  ADD CONSTRAINT `phancong_ibfk_2` FOREIGN KEY (`MaGV`) REFERENCES `giangvien` (`MaGV`),
  ADD CONSTRAINT `phancong_ibfk_3` FOREIGN KEY (`MaCB`) REFERENCES `canboql` (`MaCB`);

--
-- Các ràng buộc cho bảng `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD CONSTRAINT `sinhvien_ibfk_1` FOREIGN KEY (`MaLop`) REFERENCES `lop` (`MaLop`),
  ADD CONSTRAINT `sinhvien_ibfk_2` FOREIGN KEY (`MaKhoa`) REFERENCES `khoa` (`MaKhoa`),
  ADD CONSTRAINT `sinhvien_ibfk_3` FOREIGN KEY (`MaNganh`) REFERENCES `nganh` (`MaNganh`),
  ADD CONSTRAINT `sinhvien_ibfk_4` FOREIGN KEY (`MaCCCD`) REFERENCES `cccd` (`MaCCCD`),
  ADD CONSTRAINT `sinhvien_ibfk_5` FOREIGN KEY (`MaTK`) REFERENCES `taikhoan` (`MaTK`),
  ADD CONSTRAINT `sinhvien_ibfk_6` FOREIGN KEY (`MaNamHoc`) REFERENCES `namhoc` (`MaNamHoc`);

--
-- Các ràng buộc cho bảng `thongbao`
--
ALTER TABLE `thongbao`
  ADD CONSTRAINT `thongbao_ibfk_1` FOREIGN KEY (`MaCB`) REFERENCES `canboql` (`MaCB`);

--
-- Các ràng buộc cho bảng `tiendo`
--
ALTER TABLE `tiendo`
  ADD CONSTRAINT `tiendo_ibfk_1` FOREIGN KEY (`MaDeTai`) REFERENCES `detai` (`MaDeTai`),
  ADD CONSTRAINT `tiendo_ibfk_2` FOREIGN KEY (`MaGV`) REFERENCES `giangvien` (`MaGV`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
