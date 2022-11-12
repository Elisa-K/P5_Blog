<?php

declare(strict_types=1);

namespace App\Models\Repositories;

use PDO;
use PDOException;
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

	public function getPostById(int $id): Post
	{
		$stmt = $this->dbConnect->prepare("SELECT post.id, post.title, post.excerpt, post.content, post.featuredImage, user.username, DATE_FORMAT(post.createdAt, '%d/%m/%Y à %Hh%i') as french_created_at, DATE_FORMAT(post.updateAt, '%d/%m/%Y à %Hh%i') as french_updated_at FROM post INNER JOIN user on user.id=post.user_id WHERE post.id = ?");

		$stmt->execute([$id]);

		$row = $stmt->fetch();

		$post = new Post();
		$post->id = $row['id'];
		$post->author = $row['username'];
		$post->title = $row['title'];
		$post->excerpt = $row['excerpt'];
		$post->content = $row['content'];
		$post->featured_img = $row['featuredImage'];
		$post->created_at = $row['french_created_at'];
		$post->updated_at = $row['french_updated_at'];

		return $post;
	}

	public function getNbPosts(): int
	{
		$stmt = $this->dbConnect->query("SELECT count(id) as nb_post FROM post");
		$row = $stmt->fetch();
		$nbPost = $row['nb_post'];
		return $nbPost;
	}

	public function addPost(string $title, string $excerpt, string $featured_img, string $content): bool
	{
		try {
			$stmt = $this->dbConnect->prepare("INSERT INTO post (title, excerpt, featuredImage, content, user_id, createdAt) VALUES(:title, :excerpt, :featuredImage, :content, :userId, NOW())");


			$stmt->bindParam(':title', $title, PDO::PARAM_STR);
			$stmt->bindParam(':excerpt', $excerpt, PDO::PARAM_STR);
			$stmt->bindParam(':featuredImage', $featured_img, PDO::PARAM_STR);
			$stmt->bindParam(':content', $content, PDO::PARAM_STR);
			//TO DO : userId à modifier en fonction de l'utilisateur connecté et admin
			$userId = 1;
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$affectedLines = $stmt->execute(
				[
					':title' => $title,
					':excerpt' => $excerpt,
					':featuredImage' => $featured_img,
					':content' => $content,
					':userId' => 1
				]
			);
			return ($affectedLines > 0);
		} catch (PDOException $e) {
			var_dump($e->getMessage());
		}

	}

}