<?php

namespace App\Controller;

use App\Entity\Traitement;
use App\Form\TraitementType;
use App\Repository\TraitementRepository;
use App\Repository\ConstatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/traitement')]
class TraitementController extends AbstractController
{
    #[Route('/', name: 'app_traitement_index', methods: ['GET'])]
    public function index(TraitementRepository $traitementRepository ): Response
    { 
        return $this->render('traitement/index.html.twig', [
            'traitements' => $traitementRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_traitement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager ,ConstatRepository $repo): Response
    {
        $traitement = new Traitement();
        $form = $this->createForm(TraitementType::class, $traitement);
        $form->handleRequest($request);
        $idFromUrl = $request->query->get('id');
        $constat=$repo->find($idFromUrl);
  
        if ($form->isSubmitted() && $form->isValid()) {
            $traitement->setIdentifiant($constat);
            $entityManager->persist($traitement);
            $entityManager->flush();

            return $this->redirectToRoute('app_traitement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('traitement/new.html.twig', [
            'traitement' => $traitement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_traitement_show', methods: ['GET'])]
    public function show(Traitement $traitement): Response
    {
        return $this->render('traitement/show.html.twig', [
            'traitement' => $traitement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_traitement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Traitement $traitement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TraitementType::class, $traitement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_traitement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('traitement/edit.html.twig', [
            'traitement' => $traitement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_traitement_delete', methods: ['POST'])]
    public function delete(Request $request, Traitement $traitement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$traitement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($traitement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_traitement_index', [], Response::HTTP_SEE_OTHER);
    }
}
