<?php

namespace App\Controller;

use App\Entity\ReponseDevis;
use App\Form\ReponseDevisType;
use App\Repository\ReponseDevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/reponse/devis')]
class ReponseDevisController extends AbstractController
{
    #[Route('/', name: 'app_reponse_devis_index', methods: ['GET'])]
    public function index(ReponseDevisRepository $reponseDevisRepository): Response
    {
        return $this->render('reponse_devis/index.html.twig', [
            'reponse_devis' => $reponseDevisRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reponse_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponseDevi = new ReponseDevis();
    $form = $this->createForm(ReponseDevisType::class, $reponseDevi);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $documentFile */
        $documentFile = $form->get('documents')->getData();

        if ($documentFile) {
            $originalFilename = pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'.'.$documentFile->guessExtension();

            try {
                $documentFile->move(
                    $this->getParameter('documents_directory'), // Chemin vers le répertoire de stockage des documents
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'erreur de téléchargement de fichier
            }

            // Stockez le nom du fichier dans l'entité ReponseDevis
            $reponseDevi->setDocuments($newFilename);
        }

        // Persistez et flush l'entité ReponseDevis
        $entityManager->persist($reponseDevi);
        $entityManager->flush();

        // Redirigez l'utilisateur vers une autre page après la soumission du formulaire
        return $this->redirectToRoute('app_reponse_devis_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reponse_devis/new.html.twig', [
        'reponse_devi' => $reponseDevi,
        'form' => $form,
    ]);
    }

    #[Route('/{idRep}', name: 'app_reponse_devis_show', methods: ['GET'])]
    public function show(ReponseDevis $reponseDevi): Response
    {
        return $this->render('reponse_devis/show.html.twig', [
            'reponse_devi' => $reponseDevi,
        ]);
    }

    #[Route('/{idRep}/edit', name: 'app_reponse_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReponseDevis $reponseDevi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseDevisType::class, $reponseDevi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse_devis/edit.html.twig', [
            'reponse_devi' => $reponseDevi,
            'form' => $form,
        ]);
    }

    #[Route('/{idRep}', name: 'app_reponse_devis_delete', methods: ['POST'])]
    public function delete(Request $request, ReponseDevis $reponseDevi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponseDevi->getIdRep(), $request->request->get('_token'))) {
            $entityManager->remove($reponseDevi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}
