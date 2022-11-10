<?php

declare(strict_types=1);

namespace App\Models\Repositories;

use PDO;
use App\Models\Entities\Post;

class PostRepository
{
	public ? PDO $dbConnect;

	public function __construct($dbConnect)
	{
		$this->dbConnect = $dbConnect;
	}
	public function getAllPosts(int $start, int $nbPostPage): array
	{

		$stmt = $this->dbConnect->query("SELECT post.id, post.title, post.excerpt, post.content, post.featuredImage, user.username, DATE_FORMAT(post.createdAt, '%d/%m/%Y à %Hh%i') as french_created_at, DATE_FORMAT(post.updateAt, '%d/%m/%Y à %Hh%i') as french_updated_at FROM post INNER JOIN user on user.id=post.user_id ORDER BY post.createdAt DESC LIMIT $start, $nbPostPage");

		$posts = [];
		while ($row = $stmt->fetch()) {
			$post = new Post();
			$post->id = $row['id'];
			$post->author = $row['username'];
			$post->title = $row['title'];
			$post->excerpt = $row['excerpt'];
			$post->content = $row['content'];
			$post->featured_img = $row['featuredImage'];
			$post->created_at = $row['french_created_at'];
			$post->updated_at = $row['french_updated_at'];

			$posts[] = $post;
		}
		return $posts;
	}

	public function getNbPosts(): int
	{
		$stmt = $this->dbConnect->query("SELECT count(id) as nb_post FROM post");
		$row = $stmt->fetch();
		$nbPost = $row['nb_post'];
		return $nbPost;
	}
}