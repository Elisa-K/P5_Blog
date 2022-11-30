<?php

declare(strict_types=1);

namespace Lib\Services;

class FileManager
{
    private string $pathImg;

    public function __construct()
    {
        $this->pathImg = "assets/featured-img/";
    }
    public function saveImg(array $image): string
    {
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $name = uniqid("img_") . '_' . time() . '.' . $extension;
        move_uploaded_file($image['tmp_name'], $this->pathImg . $name);
        return $name;
    }

    public function deleteImg(string $name): bool
    {
        return unlink($this->pathImg . $name);
    }
}