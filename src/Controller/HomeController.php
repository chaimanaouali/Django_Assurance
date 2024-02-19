<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;

class HomeController extends AbstractController

{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Fetching multiple post data from the database
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll(); // Adjust the query according to your needs

        return $this->render('client/home/home-client.html.twig', [
            'posts' => $posts,
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/afficher/{id}', name: 'app_show_post')]
    public function showPost($id): Response
    {
        // Fetching single post data from the database by ID
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }

        return $this->render('client/home/show_post.html.twig', [
            'post' => $post,
            'controller_name' => 'HomeController',
        ]);
    }
}
