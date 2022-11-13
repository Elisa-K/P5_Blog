<?php

declare(strict_types=1);

namespace Lib\Services;

class FileManager
{
    public function saveImg(array $image): string
    {
        $name = str_replace(' ', '-', $image['name']);
        $path = "assets/featured-img/";
        $fileInfo = pathinfo($image['name']);
        $extension = $fileInfo['extension'];
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        if (in_array($extension, $allowedExtensions)) {
            if (!file_exists($path . $name)) {
                move_uploaded_file($image['tmp_name'], $path . $name);
            }
            return $name;
        }
        return "";
    }
}