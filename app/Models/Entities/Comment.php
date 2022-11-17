<?php

declare(strict_types=1);

namespace App\Models\Entities;

class Comment
{

	public int $id;
	public string $author;
	public string $content;
	public string $created_at;
	public int $post_id;
	public int $is_valid;

}