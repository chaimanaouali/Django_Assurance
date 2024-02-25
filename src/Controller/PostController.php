<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post_index', methods: ['GET'])]
    public function index(Request $request, PostRepository $postRepository): Response
{
    // Get the current page number from the request query parameters
    $page = $request->query->getInt('page', 1);
    // Set the number of items per page
    $perPage = 5;

    // Get the total count of posts
    $totalCount = $postRepository->count([]);

    // Calculate the total number of pages
    $pageCount = ceil($totalCount / $perPage);

    // Calculate the offset
    $offset = ($page - 1) * $perPage;
    
    // Fetch the posts for the current page
    $posts = $postRepository->findBy([], ['id' => 'ASC'], $perPage, $offset);

    // Get the total count of posts for the current session
    $session = $request->getSession();
    $previousPostCount = $session->get('previousPostCount', 0);

    // Check if there's a change in the total count of posts
    $newPostCount = $request->query->getInt('postCount', 0);
    if ($newPostCount > $previousPostCount) {
        // Set a flash message for notification
        $this->addFlash('info', 'A new post has been created!');
    }

    // Update the previous post count in the session
    $session->set('previousPostCount', $newPostCount);

    return $this->render('post/index.html.twig', [
        'posts' => $posts,
        'currentPage' => $page,
        'perPage' => $perPage,
        'totalCount' => $totalCount,
        'pageCount' => $pageCount,
    ]);
}

    
    #[Route('/post/new', name: 'app_post_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, PostRepository $postRepository): Response
{
    $post = new Post();
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($post);
        $entityManager->flush();

        // Count total posts
        $postCount = $postRepository->count([]);
        
        // Add flash message
        $this->addFlash('success', 'Post created successfully.');

        // Send email after post creation
        $email = (new Email())
            ->from('hello@example.com')
            ->to('garalibechir10@gmail.com')
            ->subject('New Post Created')
            ->text('A new post has been created!')
            ->html('<p>A new post has been created!</p>');

        $mailer->send($email);

        // Redirect to index page with updated notification count
        return $this->redirectToRoute('app_post_index', ['postCount' => $postCount]);
    }

    return $this->render('post/new.html.twig', [
        'post' => $post,
        'form' => $form->createView(),
    ]);
}
    #[Route('/post/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/post/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index');
    }

    
}
