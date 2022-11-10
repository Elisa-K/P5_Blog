<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;
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
}