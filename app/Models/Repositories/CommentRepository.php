<?php

declare(strict_types=1);

namespace App\Models\Repositories;

use PDO;
use PDOException;
use App\Models\Entities\Comment;

class CommentRepository
{
	public ? PDO $dbConnect;

	public function __construct($dbConnect)
	{
		$this->dbConnect = $dbConnect;
	}

	public function getCommentsByPostId(int $post_id): array
	{
		$stmt = $this->dbConnect->query("SELECT comment.id, comment.content, comment.isValid, user.username, DATE_FORMAT(comment.createdAt, '%d/%m/%Y à %Hh%i') as french_created_at FROM comment INNER JOIN user on user.id=comment.user_id WHERE comment.post_id = $post_id AND comment.isValid is TRUE ORDER BY comment.createdAt DESC");

		$comments = [];
		while ($row = $stmt->fetch()) {
			$comment = new Comment();
			$comment->id = $row['id'];
			$comment->author = $row['username'];
			$comment->content = $row['content'];
			$comment->created_at = $row['french_created_at'];
			$comment->is_valid = $row['isValid'];
			$comments[] = $comment;
		}
		return $comments;
	}

	public function addComment(int $postId, string $content, int $userId): bool
	{
		$stmt = $this->dbConnect->prepare("INSERT INTO comment (content, post_id, createdAt, isValid, user_id) VALUES(:content, :postId, NOW(), 0, :userId)");

		$stmt->bindParam(':content', $content, PDO::PARAM_STR);
		$stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$affectedLines = $stmt->execute();

		return ($affectedLines > 0);
	}
}