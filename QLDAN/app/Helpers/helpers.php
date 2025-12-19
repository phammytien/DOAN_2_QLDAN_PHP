<?php

use Illuminate\Support\Str;

if (!function_exists('getDisplayName')) {
    /**
     * ✅ Hiển thị tên quan hệ (Lớp / Ngành / Khoa)
     */
    function getDisplayName($object, $relation, $field)
    {
        try {
            if (!$object) return '-';

            // 1️⃣ Nếu là object Eloquent (quan hệ)
            if (isset($object->$relation) && is_object($object->$relation)) {
                return $object->$relation->$field ?? '-';
            }

            // 2️⃣ Nếu là chuỗi JSON
            if (isset($object->$relation) && is_string($object->$relation)) {
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
            // ignore
        }

        return '-';
    }
}
