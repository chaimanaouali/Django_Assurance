<?php

namespace App\Controller;

use App\Entity\Mecanicien;
use App\Form\MecanicienType;
use App\Repository\MecanicienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;

#[Route('/mecanicien')]
class MecanicienController extends AbstractController
{
    #[Route('/', name: 'app_mecanicien_index', methods: ['GET'])]
    public function index(MecanicienRepository $mecanicienRepository): Response
    {
        return $this->render('admin/base-adminM.html.twig', [
            'mecaniciens' => $mecanicienRepository->findAll(),
        ]);
    

      
    }
    
    #[Route('/new', name: 'app_mecanicien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mecanicien = new Mecanicien();
        $form = $this->createForm(MecanicienType::class, $mecanicien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'.'.$image->guessExtension();
    
                try {
                    //deplacer le fichier vers un répertoire
                    $image->move(
                        $this->getParameter('image_directory'), // Chemin vers le répertoire de stockage des documents
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'erreur de téléchargement de fichier
                }
    
                // Stockez le nom du fichier dans l'entité 
                $mecanicien->setImage($newFilename);
            }
    
            $entityManager->persist($mecanicien); // Enregistre l'objet $mecanicien dans le gestionnaire d'entités
            $entityManager->flush(); //Exécute réellement l'opération de persistance en base de données. 

            return $this->redirectToRoute('app_mecanicien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/base-NewM.html.twig', [
            'mecanicien' => $mecanicien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mecanicien_show', methods: ['GET'])]
    public function show(Mecanicien $mecanicien): Response
    {
        return $this->render('admin/base-ShowM.html.twig', [
            'mecanicien' => $mecanicien,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mecanicien_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mecanicien $mecanicien, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MecanicienType::class, $mecanicien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mecanicien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/base-EditM.html.twig', [
            'mecanicien' => $mecanicien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mecanicien_delete', methods: ['POST'])]
    public function delete(Request $request, Mecanicien $mecanicien, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mecanicien->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mecanicien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mecanicien_index', [], Response::HTTP_SEE_OTHER);
    }

    
   
}
