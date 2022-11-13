<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;
use Lib\Services\FileManager;
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

    public function newPostForm(): void
    {
        $this->view('back_office/new_post.html.twig');
    }

    public function addPost()
    {
        // 1- Vérifier utilisateur connecté et admin
        // 2- vérifier que tous les champs sont bien renseignés
        // 3- Appeller fonction pour enregister l'image (FileManager->saveImg())
        // 4- Créer un objet Post ?
        // 5- Appeller la function addPost du repository avec en param l'objet Post et l'id utilisateur connecté
        // 6- si ok : retour sur la vue tous les posts avec un message succés, si erreur afficher le message d'erreur sur la page du formulaire (remplir les champs)

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = filter_input(INPUT_POST, 'title');
            //Créer une fonction nettoyage données
            $title = trim($title);
            $title = htmlspecialchars($title);

            $excerpt = filter_input(INPUT_POST, 'excerpt');
            $excerpt = trim($excerpt);
            $excerpt = htmlspecialchars($excerpt);

            $content = filter_input(INPUT_POST, 'content');
            $content = trim($content);
            // $content = htmlspecialchars($content);

            if (!empty($title) && !empty($excerpt) && !empty($content)) {
                if ($_FILES['featured-img']['error'] == 0) {
                    $fileManager = new FileManager();
                    $featuredImg = $fileManager->saveImg($_FILES['featured-img']);
                }

                $postRepository = new PostRepository($this->getDatabase());
                $postRepository->addPost($title, $excerpt, $featuredImg, $content, 1);

                header('Location: /dashboard/posts');
            } else {
                $message = "Veuillez renseigner tous les champs.";
                // var_dump($content);
                $this->view('back_office/new_post.html.twig', ['title' => $title, 'excerpt' => $excerpt, 'content' => $content, 'message' => $message]);
            }
        }



    }
}