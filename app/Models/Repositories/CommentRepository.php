<?php

declare(strict_types=1);

namespace App\Models\Repositories;

use PDO;
use App\Models\Entities\Comment;

class CommentRepository
{
    private ?PDO $dbConnect;

    public function __construct($dbConnect)
    {
        $this->dbConnect = $dbConnect;
    }

    public function getCommentsByPostId(int $postId): array
    {
        $stmt = $this->dbConnect->query("SELECT comment.id, comment.content, comment.isValid, user.username, DATE_FORMAT(comment.createdAt, '%d/%m/%Y à %Hh%i') as french_createdAt FROM comment INNER JOIN user on user.id=comment.user_id WHERE comment.post_id = $postId AND comment.isValid is TRUE ORDER BY comment.createdAt DESC");

        $comments = [];
        while ($row = $stmt->fetch()) {
            $comment = new Comment();
            $comment->id = $row['id'];
            $comment->author = $row['username'];
            $comment->content = $row['content'];
            $comment->createdAt = $row['french_createdAt'];
            $comment->isValid = $row['isValid'];
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

    public function getCommentsToModerate(): array
    {
        $stmt = $this->dbConnect->query("SELECT comment.id, comment.content, comment.isValid, post.title, user.username, DATE_FORMAT(comment.createdAt, '%d/%m/%Y à %Hh%i') as french_createdAt FROM comment INNER JOIN post on post.id=comment.post_id INNER JOIN user on user.id=comment.user_id WHERE comment.isValid is FALSE ORDER BY comment.createdAt DESC");
        $comments = [];
        while ($row = $stmt->fetch()) {
            $comment = new Comment();
            $comment->id = $row['id'];
            $comment->author = $row['username'];
            $comment->content = $row['content'];
            $comment->createdAt = $row['french_createdAt'];
            $comment->isValid = $row['isValid'];
            $comment->postTitle = $row['title'];

            $comments[] = $comment;
        }
        return $comments;
    }

    public function validateComment(int $id): bool
    {
        $stmt = $this->dbConnect->prepare("UPDATE comment SET isValid=1 WHERE id=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $affectedLines = $stmt->execute();

        return ($affectedLines > 0);
    }

    public function deleteComment(int $id): bool
    {
        $stmt = $this->dbConnect->prepare("DELETE FROM comment WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $affectedLines = $stmt->execute();

        return ($affectedLines > 0);
    }
}
