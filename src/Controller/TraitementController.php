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
use TCPDF;
use App\Service\SmsService;

#[Route('/traitement')]
class TraitementController extends AbstractController
{

    #[Route('/', name: 'app_traitement_index', methods: ['GET'])]
    public function index(Request $request, TraitementRepository $traitementRepository): Response
    {
        
    
        return $this->render('traitement/index.html.twig', [
            'traitements' => $traitementRepository->findAll(),
        
        ]);
    }

    #[Route('/traitement/download-pdf/{id}', name: 'app_traitement_download_pdf', methods: ['GET'])]
    public function downloadPdfAction(Request $request, TraitementRepository $traitementRepository, $id): Response
    {
        // Retrieve the specific Traitement based on the provided ID
        $traitement = $traitementRepository->find($id);
    
        if (!$traitement) {
            throw $this->createNotFoundException('Traitement not found');
        }
    
        // Generate PDF content using the specific Traitement data
        $pdfContent = $this->generatePdfContent($traitement);
    
        // Create a new Symfony Response object with appropriate headers
        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename=traitement_' . $id . '.pdf');
    
        return $response;
    }
    
    private function generatePdfContent(Traitement $traitement): string
    {
        // Create a new TCPDF instance
        $pdf = new \TCPDF();
    
        // Add content to the PDF using Traitement data
            $pdf->AddPage();
            $pdf->SetFont('times', 'B', 16);
        
        // Example: Displaying Traitement data in the PDF
        $pdf->Cell(40, 10, 'Traitement ID: ' . $traitement->getId());
        $pdf->Ln(); // Move to the next line
        $pdf->Cell(40, 10, 'Responsable: ' . $traitement->getResponsable());
        $pdf->Ln(); // Move to the next line
        $pdf->Cell(40, 10, 'Statut: ' . $traitement->getStatut());
        $pdf->Ln(); // Move to the next line
        $pdf->Cell(40, 10, 'Date de Traitement: ' . $traitement->getDatetaitement()->format('Y-m-d H:i:s'));
        $pdf->Ln(); // Move to the next line
        $pdf->Cell(40, 10, 'Remarque: ' . $traitement->getRemarque());



        // Add more cells as needed for other Traitement properties
    
        // Return the generated PDF content as a string
        return $pdf->Output('S');
    }
    #[Route('/search', name: 'app_traitement_search', methods: ['POST'])]
    public function search(Request $request, TraitementRepository $traitementRepository): Response
    {
        $searchTerm = $request->request->get('search');
        $traitements = $traitementRepository->search($searchTerm);
    
        return $this->render('traitement/listAjax.html.twig', [
            'traitements' => $traitements,
        
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
