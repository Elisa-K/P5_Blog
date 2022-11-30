<?php

declare(strict_types=1);

namespace App\Models\Entities;

class Post
{
    public int $id;
    public string $author;
    public string $title;
    public string $excerpt;
    public string $content;
    public string $featuredImg;
    public string $createdAt;
    public ?string $updatedAt;
    public int $nbComment;
}
