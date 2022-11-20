<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;
use Lib\Services\FormValidator;
use App\Models\Entities\Comment;
use App\Models\Repositories\PostRepository;
use App\Models\Repositories\CommentRepository;

class BlogController extends Controller
{
    public function allPosts(): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        if ((filter_input(INPUT_GET, 'page'))) {
            $page = filter_input(INPUT_GET, 'page');
        } else {
            $page = 1;
        }
        $nbPostPage = 5;
        $start = ($page - 1) * $nbPostPage;
        $nbPost = $postRepository->getNbPosts();
        $posts = $postRepository->getAllPosts($start, $nbPostPage);
        $nbPage = ceil($nbPost / $nbPostPage);
        $this->view('front_office/blog.html.twig', ['route' => '/blog', 'posts' => $posts, 'nbPage' => $nbPage, 'actual_page' => $page]);
    }

    public function getPost(int $id): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        $post = $postRepository->getPostById($id);
        $commentRepository = new CommentRepository($this->getDatabase());
        $comments = $commentRepository->getCommentsByPostId($id);

        $this->view('front_office/single_post.html.twig', ['route' => '/blog', 'post' => $post, 'comments' => $comments]);
    }

    public function addComment(int $postId): void
    {
        $formValidator = new FormValidator();
        $content = filter_input(INPUT_POST, 'comment');
        $content = trim($content);
        $errorComment = $formValidator->checkTextLength($content, "commentaire", 5, null);

        $comment = new Comment();
        $comment->content = $content;

        if (!$errorComment) {
            $commentRepository = new CommentRepository($this->getDatabase());
            $commentRepository->addComment($postId, $content, 1);
            // TO DO : retourner message succés + indiquer que le commentaire est soumis à validation
            header('Location: /blog/post/' . $postId);
            exit();
        } else {
            $postRepository = new PostRepository($this->getDatabase());
            $post = $postRepository->getPostById($postId);
            $commentRepository = new CommentRepository($this->getDatabase());
            $comments = $commentRepository->getCommentsByPostId($postId);
            $data = ['route' => '/blog', 'post' => $post, 'comments' => $comments, 'errorComment' => $errorComment];
            $this->view('front_office/single_post.html.twig', $data);
        }


    }
}