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
use Symfony\Component\Form\FormError;



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
            // Vérifiez si l'email existe déjà dans la base de données
            $email = $reponseDevi->getEmail();
            $existingReponseDevi = $entityManager->getRepository(ReponseDevis::class)->findOneBy(['email' => $email]);
            
            if ($existingReponseDevi) {
                // Ajoutez une erreur au formulaire pour indiquer que l'email est déjà utilisé
                $form->get('email')->addError(new FormError('This email is already used.'));
                
                // Affichez à nouveau le formulaire avec les erreurs
                return $this->render('reponse_devis/new.html.twig', [
                    'reponse_devi' => $reponseDevi,
                    'form' => $form->createView(),
                ]);
            }

            // Récupérer le fichier envoyé dans le formulaire
            $documentFile = $form->get('documents')->getData();

            // Vérifier si un fichier a été téléversé
            if ($documentFile instanceof UploadedFile) {
                // Générer un nom de fichier unique
                $newFilename = uniqid().'.'.$documentFile->guessExtension();

                try {
                    // Déplacer le fichier téléversé vers le répertoire de stockage des documents
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

            // Enregistrer l'entité dans la base de données
            $entityManager->persist($reponseDevi);
            $entityManager->flush();

            // Redirection vers la page d'index après l'enregistrement réussi
            return $this->redirectToRoute('app_reponse_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rendu du formulaire avec les données de la nouvelle réponse devis
        return $this->renderForm('reponse_devis/new.html.twig', [
            'reponse_devi' => $reponseDevi,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_reponse_devis_show', methods: ['GET'])]
    public function show(ReponseDevis $reponseDevi): Response
    {
        return $this->render('reponse_devis/show.html.twig', [
            'reponse_devi' => $reponseDevi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reponse_devis_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_reponse_devis_delete', methods: ['POST'])]
    public function delete(Request $request, ReponseDevis $reponseDevi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponseDevi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponseDevi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}
