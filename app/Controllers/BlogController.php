<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;
use Lib\Services\FormValidator;
use App\Models\Entities\Comment;
use Lib\Services\SessionManager;
use Lib\Services\Form\EditUserForm;
use Lib\Services\Form\EditCommentForm;
use App\Models\Repositories\PostRepository;
use App\Models\Repositories\UserRepository;
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
        $commentForm = new EditCommentForm();
        if ($commentForm->isValid()) {
            $commentRepository = new CommentRepository($this->getDatabase());
            $commentRepository->addComment($postId, $commentForm->data['comment'], 1);
            $this->addFlashMessage(["success" => "Votre commentaire a bien été transmis ! Il est actuellement soumis à validation avant d'être publié."]);
            header('Location: /blog/post/' . $postId);
            exit();
        } else {
            $postRepository = new PostRepository($this->getDatabase());
            $post = $postRepository->getPostById($postId);
            $commentRepository = new CommentRepository($this->getDatabase());
            $comments = $commentRepository->getCommentsByPostId($postId);
            $error = $commentForm->getError();
            $this->view('front_office/single_post.html.twig', ['route' => '/blog', 'post' => $post, 'comments' => $comments, 'error' => $error]);
        }
    }

    public function signUp(): void
    {
        if ($this->isSubmit()) {
            $userForm = new EditUserForm("register");
            if ($userForm->isValid()) {
                $userRepository = new UserRepository($this->getDatabase());
                $userRepository->registerUser($userForm->data['username'], $userForm->data['firstname'], $userForm->data['lastname'], $userForm->data['email'], $userForm->data['password']);
                $this->addFlashMessage(["success" => "Votre compte a bien été créé. Vous pouvez maintenant vous connecter."]);
                header('Location: /signin');
                exit();
            } else {
                $errors = $userForm->getError();
                $this->view('front_office/sign_up.html.twig', ['route' => '/signup', 'user' => $userForm->data, 'errors' => $errors]);
            }
        } else {
            $this->view('front_office/sign_up.html.twig', ['route' => '/signup']);
        }
    }

    public function signIn(): void
    {
        if ($this->isSubmit()) {
            $userForm = new EditUserForm("login");
            if ($userForm->isValid()) {
                $userRepository = new UserRepository($this->getDatabase());
                $user = $userRepository->getUserByEmail($userForm->data['email'], $userForm->data['password']);
                if (is_string($user)) {
                    $error = ['error' => $user];
                    $this->view('front_office/sign_in.html.twig', ['route' => '/signup', 'user' => $userForm->data, 'errors' => $error]);
                } else {
                    $this->session->set('user', $user);
                    if ($user->isAdmin) {
                        header('Location: /dashboard');
                        exit();
                    }
                    header('Location: /');
                    exit();
                }
            } else {
                $errors = $userForm->getError();
                $this->view('front_office/sign_in.html.twig', ['route' => '/signup', 'user' => $userForm->data, 'errors' => $errors]);
            }
        } else {
            $this->view('front_office/sign_in.html.twig', ['route' => '/signin']);
        }
    }

    public function logOut()
    {
        $this->session->remove('user');
        $this->session->clear();
        header('Location: /');
        exit();
    }

}