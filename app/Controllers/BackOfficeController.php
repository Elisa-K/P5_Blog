<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;
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

    public function addPost()
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

            if (!$errorTitle && !$errorExcerpt && !$errorContent) {
                if ($_FILES['featured-img']['error'] == 0) {
                    $fileManager = new FileManager();
                    $featuredImg = $fileManager->saveImg($_FILES['featured-img']);
                    if (is_array($featuredImg)) {
                        $errorImg = $featuredImg['error'];
                        $data = ['title' => $title, 'excerpt' => $excerpt, 'content' => $content, 'errorImg' => $errorImg];
                    } else {
                        $postRepository = new PostRepository($this->getDatabase());
                        $postRepository->addPost($title, $excerpt, $featuredImg, $content, 1);
                        header('Location: /dashboard/posts');
                        exit();
                    }
                } else {
                    $message = "Une erreur s'est produite pendant le téléchargement de votre image.";
                    $data = ['title' => $title, 'excerpt' => $excerpt, 'content' => $content, 'message' => $message];
                }
            } else {
                $data = ['title' => $title, 'excerpt' => $excerpt, 'content' => $content, 'errorTitle' => $errorTitle, 'errorExcerpt' => $errorExcerpt, 'errorContent' => $errorContent];
            }
        }
        $this->view('back_office/new_post.html.twig', $data);
    }
}