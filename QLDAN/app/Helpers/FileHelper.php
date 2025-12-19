<?php

namespace App\Helpers;

use App\Models\File;

class FileHelper
{
    /**
     * ✅ Trả về URL đầy đủ của file
     */
    /**
     * ✅ Trả về URL đầy đủ của file
     */
    public static function getFileUrl($path)
    {
        if (!$path) return asset('img/default-file.png');
        if (str_starts_with($path, 'http')) return $path;
        
        // Nếu path đã chứa thư mục gốc (vd: storage/...) thì dùng trực tiếp
        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        return asset('img/uploads/' . $path);
    }

    /**
     * ✅ Upload file vào thư mục img/uploads (hoặc custom rootFolder)
     */
    public static function uploadFile($file, $subDirectory = '', $rootFolder = 'img/uploads')
    {
        if (!$file) return null;

        $basePath = public_path($rootFolder);
        $destinationPath = $subDirectory ? $basePath . DIRECTORY_SEPARATOR . $subDirectory : $basePath;

        if (!file_exists($destinationPath)) mkdir($destinationPath, 0777, true);

        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $fileName);

        $fileModel = new File();
        $fileModel->name = $file->getClientOriginalName();
        
        // Nếu dùng rootFolder mặc định, giữ nguyên logic cũ (chỉ lưu subDir/file)
        if ($rootFolder === 'img/uploads') {
            $fileModel->path = $subDirectory ? ($subDirectory . '/' . $fileName) : $fileName;
        } else {
            // Nếu dùng rootFolder khác (vd: storage), lưu full path relative to public
            $fileModel->path = $rootFolder . '/' . ($subDirectory ? $subDirectory . '/' . $fileName : $fileName);
        }

        $fileModel->type = $file->getClientMimeType();
        $fileModel->size = filesize($destinationPath . DIRECTORY_SEPARATOR . $fileName);
        $fileModel->extension = $file->getClientOriginalExtension();
        $fileModel->is_deleted = false;
        $fileModel->save();

        return $fileModel;
    }

    /**
     * ✅ Lấy danh sách URL file (nếu có)
     */
    public static function getFiles($modelFiles)
    {
        $files = [];
        foreach ($modelFiles as $modelFile) {
            if ($modelFile->file) $files[] = self::getFileUrl($modelFile->file->path);
        }
        return empty($files) ? [self::getFileUrl(null)] : $files;
    }

    /**
     * ✅ Hàm hiển thị tên quan hệ (Lớp / Ngành / Khoa)
     * Dùng trong Blade: {{ FileHelper::getDisplayName($sv, 'Lop', 'TenLop') }}
     */
    public static function getDisplayName($object, $relation, $field)
    {
        try {
            // 1️⃣ Nếu là object Eloquent (quan hệ)
            if (isset($object->$relation) && is_object($object->$relation)) {
                return $object->$relation->$field ?? '-';
            }

            // 2️⃣ Nếu là chuỗi JSON
            if (is_string($object->$relation)) {
                $json = json_decode($object->$relation, true);
                if (is_array($json) && isset($json[$field])) {
                    return $json[$field];
                }
            }

            // 3️⃣ Nếu là mảng PHP (sau khi decode)
            if (is_array($object->$relation) && isset($object->$relation[$field])) {
                return $object->$relation[$field];
            }
        } catch (\Throwable $e) {
            // Không làm gì, chỉ để tránh lỗi
        }

        // 4️⃣ Trường hợp mặc định
        return '-';
    }
}