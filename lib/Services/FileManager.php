<?php

declare(strict_types=1);

namespace Lib\Services;

class FileManager
{
    public function saveImg(array $image): string|array
    {

        $path = "assets/featured-img/";
        $fileType = $image['type'];
        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

        if (in_array($fileType, $allowedTypes)) {
            $fileSize = $image['size'];
            if ($fileSize < 5 * 1024 * 1024) {
                $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
                $name = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 5) . '-' . time() . '.' . $extension;
                if (move_uploaded_file($image['tmp_name'], $path . $name)) {
                    return $name;
                } else {
                    return ['erreur' => "Une erreur s'est produite pendant l'enregistrement de l'image."];
                }
            } else {
                return ['erreur' => "La taille de l'image ne doit pas exéder 5Mb"];
            }

        } else {
            return ['erreur' => "L'extension de l'image n'est pas autorisée ! (acceptées : jpg, jpeg et png)"];
        }

    }
}