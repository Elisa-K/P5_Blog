<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;
use App\Models\Entities\Post;
use Lib\Services\FileManager;
use Lib\Services\Form\EditPostForm;
use App\Models\Repositories\PostRepository;
use App\Models\Repositories\UserRepository;
use App\Models\Repositories\CommentRepository;

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
        if ($this->isSubmit()) {
            $postForm = new EditPostForm("add");
            if ($postForm->isValid()) {
                $postRepository = new PostRepository($this->getDatabase());
                $fileManager = new FileManager();
                $featuredImg = $fileManager->saveImg($postForm->data['featuredImg']);
                $postRepository->addPost($postForm->data['title'], $postForm->data['excerpt'], $featuredImg, $postForm->data['content'], 1);
                $this->addFlashMessage(["success" => "Votre article a bien été publié !"]);
                header('Location: /dashboard/posts');
                exit();
            } else {
                $errors = $postForm->getError();
                $this->view('back_office/new_post.html.twig', ["errors" => $errors, 'post' => $postForm->data]);
            }
        } else {
            $this->view('back_office/new_post.html.twig');
        }
    }

    public function updatePost(int $id): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        $post = $postRepository->getPostById($id);
        // TO DO Vérifier utilisateur connecté et admin
        if ($this->isSubmit()) {
            $postForm = new EditPostForm("update");
            if ($postForm->isValid()) {
                $featuredImg = $post->featuredImg;
                if ($postForm->data['featuredImg']) {
                    $fileManager = new FileManager();
                    $featuredImg = $fileManager->saveImg($postForm->data['featuredImg']);
                    $fileManager->deleteImg($post->featuredImg);
                }
                $postRepository->updatePost($id, $postForm->data['title'], $postForm->data['excerpt'], $featuredImg, $postForm->data['content']);
                $this->addFlashMessage(["success" => "Votre article a bien été mise à jour !"]);

                header('Location: /dashboard/posts');
                exit();
            } else {
                $errors = $postForm->getError();
                $post = $postForm->data;
                $post['id'] = $id;
                $this->view('back_office/update_post.html.twig', ["errors" => $errors, 'post' => $post]);
            }
        } else {
            $this->view('back_office/update_post.html.twig', ['post' => $post]);
        }
    }

    public function deletePost(int $id): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        $featuredImg = $postRepository->getFeaturedImg($id);
        if ($postRepository->deletePost($id)) {
            $fileManager = new FileManager();
            $fileManager->deleteImg($featuredImg);
            $this->addFlashMessage(["success" => "L'article a bien été supprimé !"]);
        } else {
            $this->addFlashMessage(["danger" => "Une erreur s'est produite lors de la suppression de l'article !"]);
        }
        header('Location: /dashboard/posts');
        exit();
    }

    public function allCommentsToModerate(): void
    {
        $commentRepository = new CommentRepository($this->getDatabase());
        $comments = $commentRepository->getCommentsToModerate();
        $this->view('back_office/moderate_comment.html.twig', ['route' => '/dashboard/moderation', 'comments' => $comments]);
    }

    public function validateComment(int $id): void
    {
        $commentRepository = new CommentRepository($this->getDatabase());
        if ($commentRepository->validateComment($id)) {
            $this->addFlashMessage(["success" => "Le commentaire a bien été validé !"]);
        } else {
            $this->addFlashMessage(["danger" => "Une erreur s'est produite lors de la validation du commentaire !"]);
        }
        header('Location: /dashboard/moderation');
        exit();
    }

    public function deleteComment(int $id): void
    {
        $commentRepository = new CommentRepository($this->getDatabase());
        if ($commentRepository->deleteComment($id)) {
            $this->addFlashMessage(["success" => "Le commentaire a bien été supprimé !"]);
        } else {
            $this->addFlashMessage(["danger" => "Une erreur s'est produite lors de la suppression du commentaire !"]);
        }
        header('Location: /dashboard/moderation');
        exit();
    }

    public function allUser(): void
    {
        $userRepository = new UserRepository($this->getDatabase());
        $adminConnected = $this->session->get('user');
        $users = $userRepository->getAllUser($adminConnected->id);
        $this->view('back_office/users.html.twig', ['route' => '/dashboard/users', 'users' => $users]);
    }

    public function allowPermissionAdmin(int $id)
    {
        $userRepository = new UserRepository($this->getDatabase());
        $user = $userRepository->getUserById($id);
        if ($userRepository->setPermissionUser($id, TRUE)) {
            $this->addFlashMessage(["success" => "Le droit administrateur a bien été donné à $user->firstname $user->lastname"]);
        } else {
            $this->addFlashMessage(["error" => "Une erreur s'est produite. Le droit administrateur n'a pu être donné à $user->firstname $user->lastname"]);
        }
        header('Location: /dashboard/users');
        exit();
    }

    public function denyPermissionAdmin(int $id)
    {
        $userRepository = new UserRepository($this->getDatabase());
        $user = $userRepository->getUserById($id);
        if ($userRepository->setPermissionUser($id, FALSE)) {
            $this->addFlashMessage(["success" => "Le droit administrateur a bien été retiré à $user->firstname $user->lastname"]);
        } else {
            $this->addFlashMessage(["error" => "Une erreur s'est produite. Le droit administrateur n'a pu être retiré à $user->firstname $user->lastname"]);
        }
        header('Location: /dashboard/users');
        exit();
    }
}