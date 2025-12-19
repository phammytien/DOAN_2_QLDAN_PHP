<?php

namespace App\Helpers;

use App\Models\File;

class ImageHelper
{
    /**
     * Lấy đường dẫn ảnh đầy đủ
     */
    public static function getImageUrl($path)
    {
        if (!$path) {
            return asset('img/default-jewelry.jpg'); // ảnh mặc định
        }

        // Nếu đã có đường dẫn đầy đủ
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // Nếu là đường dẫn tương đối
        return asset('img/uploads/images/' . $path);
    }

    /**
     * Lấy ảnh chính của jewelry
     */
    public static function getMainImage($jewelry)
    {
        if (!$jewelry) return self::getImageUrl(null);

        $mainFile = $jewelry->jewelryFiles->where('is_main', 1)->first();

        if ($mainFile && $mainFile->file) {
            return self::getImageUrl($mainFile->file->path);
        }

        // Lấy ảnh đầu tiên nếu không có ảnh chính
        $firstFile = $jewelry->jewelryFiles->first();
        if ($firstFile && $firstFile->file) {
            return self::getImageUrl($firstFile->file->path);
        }

        return self::getImageUrl(null);
    }

    /**
     * Lấy tất cả ảnh của jewelry
     */
    public static function getAllImages($jewelry)
    {
        if (!$jewelry) return [self::getImageUrl(null)];

        $images = [];
        foreach ($jewelry->jewelryFiles as $jewelryFile) {
            if ($jewelryFile->file) {
                $images[] = self::getImageUrl($jewelryFile->file->path);
            }
        }

        return empty($images) ? [self::getImageUrl(null)] : $images;
    }

    /**
     * Lấy icon category
     */
    public static function getCategoryIcon($category)
    {
        if (!$category || !$category->file) {
            return asset('img/icon-s1.png'); // icon mặc định
        }

        return self::getImageUrl($category->file->path);
    }

    /**
     * Upload và lưu file
     */
    public static function uploadFile($file, $directory = 'images')
    {
        if (!$file) return null;

        $destinationPath = public_path("img/uploads/{$directory}");

        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $fileName);

        // Lưu vào database
        $fileModel = new File();
        $fileModel->name = $file->getClientOriginalName();
        $fileModel->path = $fileName;
        $fileModel->type = $file->getClientMimeType();
        $fileModel->size = filesize($destinationPath . DIRECTORY_SEPARATOR . $fileName);
        $fileModel->extension = $file->getClientOriginalExtension();
        $fileModel->is_deleted = false;
        $fileModel->save();

        return $fileModel;
    }
}