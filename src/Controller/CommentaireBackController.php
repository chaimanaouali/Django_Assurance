<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\Commentaire1Type;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;



#[Route('/commentaire/back')]
class CommentaireBackController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_back_index', methods: ['GET'])]
   
public function index(CommentaireRepository $commentaireRepository, Request $request, PaginatorInterface $paginator): Response
{
    // Get the search query from the request
    $query = $request->query->get('q');

    

    // Create a query builder for the Commentaire entity
    $queryBuilder = $commentaireRepository->createQueryBuilder('c')
        ->orderBy('c.dateCreation', 'DESC');

    // If a search query is provided, add a condition to search for commentaires
    if ($query) {
        $queryBuilder
            ->where('c.contenu LIKE :query')
            ->setParameter('query', '%' . $query . '%');
    }

    // Get the paginated results
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(), // Use the query builder to build the query
        $request->query->getInt('page', 1), // Get the current page number
        10 // Number of items per page
     );
    $signaledComments = $commentaireRepository->findBy(['signaled' => true]);
    // Render the template with the pagination
    return $this->render('commentaire_back/index.html.twig', [
        'pagination' => $pagination,
        'totalSignaledComments' => count($signaledComments)
    ]);
}

    #[Route('/new', name: 'app_commentaire_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(Commentaire1Type::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire_back/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_back_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire_back/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Commentaire1Type::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire_back/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_back_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_back_index', [], Response::HTTP_SEE_OTHER);
    }
   
}
