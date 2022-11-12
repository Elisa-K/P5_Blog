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
		// 1- vérifier que tous les champs sont bien renseigné
		// 2- Appeller fonction pour enregister l'image (FileManager->saveImg())
		// 3- Créer un objet Post ?
		// 4- Appeller la function addPost du repository avec en param l'objet Post
		// 5- si ok : retour sur la vue tous les posts avec un message, si erreur afficher le message d'erreur sur la page du formulaire (remplir les champs)
		$fileManager = new FileManager();
		$featuredImg = $fileManager->saveImg($_FILES['featured-img']);
		$title = filter_input(INPUT_POST, 'title');
		$excerpt = filter_input(INPUT_POST, 'excerpt');
		$content = filter_input(INPUT_POST, 'content');
		$postRepository = new PostRepository($this->getDatabase());
		$postRepository->addPost($title, $excerpt, $featuredImg, $content);
		header('Location: /dashboard/posts');
	}
}