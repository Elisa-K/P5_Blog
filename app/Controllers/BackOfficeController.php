<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;
use Lib\Services\FileManager;
use App\Form\EditPostForm;
use App\Models\Repositories\{PostRepository, UserRepository, CommentRepository};

class BackOfficeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkIsAdmin();
    }
    public function dashboard(): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        $userRepository = new UserRepository($this->getDatabase());
        $commentRepository = new CommentRepository($this->getDatabase());
        $nbPost = $postRepository->getNbPosts();
        $lastPosts = $postRepository->getAllPosts(0, 5);
        $nbUser = $userRepository->getNbUser();
        $nbAdmin = $userRepository->getNbAdmin();
        $nbComment = $commentRepository->getNbCommentToModerate();

        $this->view('back_office/dashboard.html.twig', ['route' => '/dashboard', 'nbPost' => $nbPost, 'lastPosts' => $lastPosts, 'nbComment' => $nbComment, 'nbUser' => $nbUser, 'nbAdmin' => $nbAdmin]);
    }

    public function allPosts(): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        $page = filter_input(INPUT_GET, 'page') ? filter_input(INPUT_GET, 'page') : 1;
        $nbPostPage = 20;
        $start = ($page - 1) * $nbPostPage;
        $nbPost = $postRepository->getNbPosts();
        $posts = $postRepository->getAllPosts($start, $nbPostPage);
        $nbPage = ceil($nbPost / $nbPostPage);

        $this->view('back_office/all_posts.html.twig', ['route' => '/dashboard/posts', 'posts' => $posts, 'nbPage' => $nbPage, 'actual_page' => $page]);
    }

    public function addPost(): void
    {
        $postForm = new EditPostForm("add");

        if ($this->isSubmit() && $postForm->isValid()) {
            if (!$this->isValidToken()) {
                $this->redirect('/dashboard/newpost');
            }

            $postRepository = new PostRepository($this->getDatabase());
            $fileManager = new FileManager();

            $featuredImg = $fileManager->saveImg($postForm->data['featuredImg']);
            $postRepository->addPost($postForm->data['title'], $postForm->data['excerpt'], $featuredImg, $postForm->data['content'], $this->session->get('user')->id);

            $this->addFlashMessage(["success" => "Votre article a bien ??t?? publi?? !"]);
            $this->redirect('/dashboard/posts');
        }

        $this->view('back_office/new_post.html.twig', ["errors" => $postForm->getError(), 'post' => $postForm->data, 'token' => $this->createToken()]);
    }

    public function updatePost(int $id): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        $post = $postRepository->getPostById($id);
        $postForm = new EditPostForm("update");

        if ($this->isSubmit()) {
            if (!$this->isValidToken()) {
                $this->redirect('/dashboard/updatepost/' . $id);
            }

            if ($postForm->isValid()) {
                $featuredImg = $post->featuredImg;
                if (array_key_exists('featuredImg', $postForm->data)) {
                    $fileManager = new FileManager();
                    $featuredImg = $fileManager->saveImg($postForm->data['featuredImg']);
                    $fileManager->deleteImg($post->featuredImg);
                }
                $postRepository->updatePost($id, $postForm->data['title'], $postForm->data['excerpt'], $featuredImg, $postForm->data['content'], $this->session->get('user')->id);
                $this->addFlashMessage(["success" => "Votre article a bien ??t?? mise ?? jour !"]);
                $this->redirect('/dashboard/posts');
            }
        }

        $dataUpdate = array_filter($postForm->data);
        $post = array_merge((array) $post, $dataUpdate);

        $this->view('back_office/update_post.html.twig', ['post' => $post, "errors" => $postForm->getError(), 'token' => $this->createToken()]);
    }

    public function deletePost(int $id): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        $featuredImg = $postRepository->getFeaturedImg($id);

        if ($postRepository->deletePost($id)) {
            $fileManager = new FileManager();
            $fileManager->deleteImg($featuredImg);
            $this->addFlashMessage(["success" => "L'article a bien ??t?? supprim?? !"]);
        } else {
            $this->addFlashMessage(["danger" => "Une erreur s'est produite lors de la suppression de l'article !"]);
        }

        $this->redirect('/dashboard/posts');
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
            $this->addFlashMessage(["success" => "Le commentaire a bien ??t?? valid?? !"]);
        } else {
            $this->addFlashMessage(["danger" => "Une erreur s'est produite lors de la validation du commentaire !"]);
        }

        $this->redirect('/dashboard/moderation');
    }

    public function deleteComment(int $id): void
    {
        $commentRepository = new CommentRepository($this->getDatabase());

        if ($commentRepository->deleteComment($id)) {
            $this->addFlashMessage(["success" => "Le commentaire a bien ??t?? supprim?? !"]);
        } else {
            $this->addFlashMessage(["danger" => "Une erreur s'est produite lors de la suppression du commentaire !"]);
        }

        $this->redirect('/dashboard/moderation');
    }

    public function allUser(): void
    {
        $userRepository = new UserRepository($this->getDatabase());
        $adminConnected = $this->session->get('user');
        $users = $userRepository->getAllUser($adminConnected->id);

        $this->view('back_office/users.html.twig', ['route' => '/dashboard/users', 'users' => $users]);
    }

    public function allowPermissionAdmin(int $id): void
    {
        $userRepository = new UserRepository($this->getDatabase());
        $user = $userRepository->getUserById($id);

        if ($userRepository->setPermissionUser($id, true)) {
            $this->addFlashMessage(["success" => "Le droit administrateur a bien ??t?? donn?? ?? $user->firstname $user->lastname"]);
        } else {
            $this->addFlashMessage(["error" => "Une erreur s'est produite. Le droit administrateur n'a pu ??tre donn?? ?? $user->firstname $user->lastname"]);
        }

        $this->redirect('/dashboard/users');
    }

    public function denyPermissionAdmin(int $id): void
    {
        $userRepository = new UserRepository($this->getDatabase());
        $user = $userRepository->getUserById($id);

        if ($userRepository->setPermissionUser($id, false)) {
            $this->addFlashMessage(["success" => "Le droit administrateur a bien ??t?? retir?? ?? $user->firstname $user->lastname"]);
        } else {
            $this->addFlashMessage(["error" => "Une erreur s'est produite. Le droit administrateur n'a pu ??tre retir?? ?? $user->firstname $user->lastname"]);
        }

        $this->redirect('/dashboard/users');
    }
}