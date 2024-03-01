<?php

namespace App\Controller;

use App\Entity\Mecanicien;
use App\Form\Mecanicien1Type;
use App\Repository\MecanicienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mecanicien_front')]
class MecanicienFrontController extends AbstractController
{
    #[Route('/', name: 'app_mecanicien_front_index', methods: ['GET'])]
    public function index(MecanicienRepository $mecanicienRepository): Response
    {
        return $this->render('mecanicien_front/index.html.twig', [
            'mecaniciens' => $mecanicienRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mecanicien_front_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mecanicien = new Mecanicien();
        $form = $this->createForm(Mecanicien1Type::class, $mecanicien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mecanicien);
            $entityManager->flush();

            return $this->redirectToRoute('app_mecanicien_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mecanicien_front/new.html.twig', [
            'mecanicien' => $mecanicien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mecanicien_front_show', methods: ['GET'])]
    public function show(Mecanicien $mecanicien): Response
    {
        return $this->render('mecanicien_front/show.html.twig', [
            'mecanicien' => $mecanicien,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mecanicien_front_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mecanicien $mecanicien, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Mecanicien1Type::class, $mecanicien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mecanicien_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mecanicien_front/edit.html.twig', [
            'mecanicien' => $mecanicien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mecanicien_front_delete', methods: ['POST'])]
    public function delete(Request $request, Mecanicien $mecanicien, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mecanicien->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mecanicien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mecanicien_front_index', [], Response::HTTP_SEE_OTHER);
    }
}
