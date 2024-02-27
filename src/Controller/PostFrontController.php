<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\Post1Type;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentaireRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;
use Knp\Component\Pager\PaginatorInterface;



#[Route('/post_front')]
class PostFrontController extends AbstractController
{  
    private $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
  #[Route('/', name: 'app_post_front_index', methods: ['GET'])]
public function index(Request $request, PostRepository $postRepository, CommentaireRepository $commentaireRepository, PaginatorInterface $paginator): Response
{
    // Get the search query from the request
    $searchQuery = $request->query->get('q');

    // If there's a search query, filter posts based on it
    if ($searchQuery) {
        $postsQuery = $postRepository->searchPosts($searchQuery);
    } else {
        // Fetch posts ordered by likes descending
        $postsQuery = $postRepository->findBy([], ['likeCount' => 'DESC']);
    }

    // Manually paginate the posts
    $currentPage = $request->query->getInt('page', 1); // Get current page number
    $perPage = 5; // Items per page
    $totalPosts = count($postsQuery); // Total number of posts
    $totalPages = ceil($totalPosts / $perPage); // Total number of pages
    $offset = ($currentPage - 1) * $perPage; // Offset for pagination
    $posts = array_slice($postsQuery, $offset, $perPage); // Get posts for current page

    // Fetch comments for each post
    $postComments = [];
    foreach ($posts as $post) {
        $postComments[$post->getId()] = $commentaireRepository->findBy(['post' => $post]);
    }

    return $this->render('post_front/index.html.twig', [
        'posts' => $posts, // Pass the paginated posts to the template
        'postComments' => $postComments,
        'searchQuery' => $searchQuery, // Pass the search query to the template
        'totalPosts' => $totalPosts, // Pass the total number of posts
        'perPage' => $perPage, // Pass the number of items per page
        'currentPage' => $currentPage, // Pass the current page number
        'totalPages' => $totalPages, // Pass the total number of pages
    ]);
}
    

    #[Route('/new', name: 'app_post_front_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(Post1Type::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post_front/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_front_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post_front/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/like/{id}', name: 'app_post_front_like', methods: ['POST'])]
    public function like(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        // Increment the like count for the post
        $post->setLikeCount($post->getLikeCount() + 1);
        $entityManager->flush();

        // Redirect back to the previous page or any other desired page
        return $this->redirectToRoute('app_post_front_index', ['id' => $post->getId()]);
    }

    #[Route('/{id}/edit', name: 'app_post_front_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Post1Type::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post_front/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_front_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_front_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/share', name: 'app_post_front_share', methods: ['POST'])]
    public function share(Request $request, Post $post, CommentaireRepository $commentaireRepository, LoggerInterface $logger): Response
    {
        try {
            // Fetch comments for the post
            $comments = $commentaireRepository->findBy(['post' => $post]);
    
            // Construct email content
            $emailContent = $this->renderView('email/share_post.html.twig', [
                'post' => $post,
                'comments' => $comments,
            ]);
    
            // Get recipient email address from the form
            $recipientEmail = 'garalibechir10@gmail.com';
    
            // Compose the email
            $email = (new Email())
                ->from('kharrat.raed@esprit.tn') // Change this to your email
                ->to($recipientEmail)
                ->subject('Check out this post and its comments')
                ->html($emailContent);
    
            // Send the email
            $this->mailer->send($email);
    
            // Log successful email sending
            $logger->info('Email sent successfully.');
    
            // Redirect or return a response
            return $this->redirectToRoute('app_post_front_index');
        } catch (\Exception $e) {
            // Log any errors
            $logger->error('Error sending email: ' . $e->getMessage());
    
            // Handle the error gracefully, e.g., show a flash message to the user
            $this->addFlash('error', 'Failed to send email. Please try again later.');
    
            // Redirect or return a response
           return $this->redirectToRoute('app_post_front_index');
          
        }
    }
   }
