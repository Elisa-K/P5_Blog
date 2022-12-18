<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;
use App\Form\{EditUserForm, EditCommentForm};
use App\Models\Repositories\{PostRepository, UserRepository, CommentRepository};

class BlogController extends Controller
{
    public function allPosts(): void
    {
        $postRepository = new PostRepository($this->getDatabase());
        $page = filter_input(INPUT_GET, 'page') ? filter_input(INPUT_GET, 'page') : 1;
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
        $this->view('front_office/single_post.html.twig', ['route' => '/blog', 'post' => $post, 'comments' => $comments, 'token' => $this->createToken()]);
    }

    public function addComment(int $postId): void
    {
        $this->checkUserConnect();
        $commentForm = new EditCommentForm();

        if ($this->isSubmit() && $commentForm->isValid()) {
            if (!$this->isValidToken())
                $this->redirect('/blog/post/' . $postId);

            $commentRepository = new CommentRepository($this->getDatabase());
            $commentRepository->addComment($postId, $commentForm->data['comment'], $this->session->get('user')->id);
            $this->addFlashMessage(["success" => "Votre commentaire a bien été transmis ! Il est actuellement soumis à validation avant d'être publié."]);
            $this->redirect('/blog/post/' . $postId);
        }

        $postRepository = new PostRepository($this->getDatabase());
        $post = $postRepository->getPostById($postId);
        $commentRepository = new CommentRepository($this->getDatabase());
        $comments = $commentRepository->getCommentsByPostId($postId);

        $this->view('front_office/single_post.html.twig', ['route' => '/blog', 'post' => $post, 'comments' => $comments, 'error' => $commentForm->getError(), 'token' => $this->createToken()]);
    }

    public function signUp(): void
    {
        $userForm = new EditUserForm("register");

        if ($this->isSubmit() && $userForm->isValid()) {
            if (!$this->isValidToken())
                $this->redirect('/signup');

            $userRepository = new UserRepository($this->getDatabase());
            $userRepository->registerUser($userForm->data['username'], $userForm->data['firstname'], $userForm->data['lastname'], $userForm->data['email'], $userForm->data['password']);
            $this->addFlashMessage(["success" => "Votre compte a bien été créé. Vous pouvez maintenant vous connecter."]);
            $this->redirect('/signin');
        }

        $this->view('front_office/sign_up.html.twig', ['route' => '/signup', 'user' => $userForm->data, 'errors' => $userForm->getError(), 'token' => $this->createToken()]);
    }

    public function signIn(): void
    {
        $userForm = new EditUserForm("login");
        $errors = [];

        if ($this->isSubmit() && $userForm->isValid()) {
            if (!$this->isValidToken())
                $this->redirect('/signin');

            $userRepository = new UserRepository($this->getDatabase());
            $user = $userRepository->getUserByEmail($userForm->data['email']);
            if ($user && password_verify($userForm->data['password'], $user->password)) {
                $this->session->set('user', $user);
                $user->isAdmin ? $this->redirect('/dashboard') : $this->redirect('/');
            }
            $errors = ['error' => 'Adresse mail inconnu ou mot de passe invalide'];
        } else {
            $errors = $userForm->getError();
        }

        $this->view('front_office/sign_in.html.twig', ['route' => '/signin', 'user' => $userForm->data, 'errors' => $errors, 'token' => $this->createToken()]);
    }

    public function logOut(): void
    {
        $this->session->remove('user');
        $this->redirect('/');
    }
}