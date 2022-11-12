<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;
use App\Models\Repositories\PostRepository;

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
		// $postRepositoy = new PostRepository($this->getDatabase());
		$this->view('front_office/single_post.html.twig', ['route' => '/blog']);
	}

}