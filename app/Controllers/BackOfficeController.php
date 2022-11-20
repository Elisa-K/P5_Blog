<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;
use App\Models\Entities\Post;
use Lib\Services\FileManager;
use Lib\Services\FormValidator;
use App\Models\Repositories\PostRepository;

class BackOfficeController extends Controller
{
    public function dashboard(): void
    {
        $this->view('back_office/dashboard.html.twig', ['route' => '/dashboard']);
    }

    public function allPosts(): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        if ((filter_input(INPUT_GET, 'page'))) {
            $page = filter_input(INPUT_GET, 'page');
        } else {
            $page = 1;
        }
        $nbPostPage = 20;
        $start = ($page - 1) * $nbPostPage;
        $nbPost = $postRepository->getNbPosts();
        $posts = $postRepository->getAllPosts($start, $nbPostPage);
        $nbPage = ceil($nbPost / $nbPostPage);

        $this->view('back_office/all_posts.html.twig', ['route' => '/dashboard/posts', 'posts' => $posts, 'nbPage' => $nbPage, 'actual_page' => $page]);
    }

    public function addPost(): void
    {
        // TO DO Vérifier utilisateur connecté et admin

        $data = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formValidator = new FormValidator();
            $title = filter_input(INPUT_POST, 'title');
            $title = trim($title);
            $errorTitle = $formValidator->checkTextLength($title, "titre", 5, 255);
            $excerpt = filter_input(INPUT_POST, 'excerpt');
            $excerpt = trim($excerpt);
            $errorExcerpt = $formValidator->checkTextLength($excerpt, "chapô", 5, null);
            $content = filter_input(INPUT_POST, 'content');
            $content = trim($content);
            $errorContent = $formValidator->checkTextLength($content, "contenu de l'article", 25, null);
            $post = new Post();
            $post->title = $title;
            $post->excerpt = $excerpt;
            $post->content = $content;

            if (!$errorTitle && !$errorExcerpt && !$errorContent) {
                if ($_FILES['featured-img']['error'] == 0) {
                    $fileManager = new FileManager();
                    $featuredImg = $fileManager->saveImg($_FILES['featured-img']);
                    if (is_array($featuredImg)) {
                        $errorImg = $featuredImg['error'];
                        $data = ['post' => $post, 'errorImg' => $errorImg];
                    } else {
                        $postRepository = new PostRepository($this->getDatabase());
                        $postRepository->addPost($title, $excerpt, $featuredImg, $content, 1);
                        header('Location: /dashboard/posts');
                        exit();
                    }
                } else {
                    $message = "Une erreur s'est produite pendant le téléchargement de votre image.";
                    $data = ['post' => $post, 'message' => $message];
                }
            } else {
                $data = ['post' => $post, 'errorTitle' => $errorTitle, 'errorExcerpt' => $errorExcerpt, 'errorContent' => $errorContent];
            }
        }
        $this->view('back_office/new_post.html.twig', $data);
    }

    public function updatePost(int $id): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        $post = $postRepository->getPostById($id);
        $data = ['post' => $post];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formValidator = new FormValidator();
            $title = filter_input(INPUT_POST, 'title');
            $title = trim($title);
            $errorTitle = $formValidator->checkTextLength($title, "titre", 5, 255);
            $excerpt = filter_input(INPUT_POST, 'excerpt');
            $excerpt = trim($excerpt);
            $errorExcerpt = $formValidator->checkTextLength($excerpt, "chapô", 5, null);
            $content = filter_input(INPUT_POST, 'content');
            $content = trim($content);
            $errorContent = $formValidator->checkTextLength($content, "contenu de l'article", 25, null);
            $postUpdate = new Post();
            $postUpdate->title = $title;
            $postUpdate->excerpt = $excerpt;
            $postUpdate->content = $content;

            if (!$errorTitle && !$errorExcerpt && !$errorContent) {
                if ($_FILES['featured-img']['error'] == 0) {
                    $fileManager = new FileManager();
                    $featuredImg = $fileManager->saveImg($_FILES['featured-img']);
                    if (is_array($featuredImg)) {
                        $errorImg = $featuredImg['error'];
                        $data = ['post' => $postUpdate, 'errorImg' => $errorImg];
                    } else {
                        $postRepository = new PostRepository($this->getDatabase());
                        $postRepository->updatePost($post->id, $title, $excerpt, $featuredImg, $content);
                        header('Location: /dashboard/posts');
                        exit();
                    }
                } else {
                    $message = "Une erreur s'est produite pendant le téléchargement de votre image.";
                    $data = ['post' => $postUpdate, 'message' => $message];
                }
            } else {
                $data = ['post' => $postUpdate, 'errorTitle' => $errorTitle, 'errorExcerpt' => $errorExcerpt, 'errorContent' => $errorContent];
            }

        }
        $this->view('back_office/update_post.html.twig', $data);

    }

    public function deletePost(int $id): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        $featuredImg = $postRepository->getFeaturedImg($id);
        if ($postRepository->deletePost($id)) {
            $fileManager = new FileManager();
            $fileManager->deleteImg($featuredImg);
            // retour avec un message succés
        } else {
            // retour avec un message erreur
        }
        header('Location: /dashboard/posts');
        exit();

    }


}