<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\Contrat2Type;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/contrat/back')]
class ContratBackController extends AbstractController
{
    #[Route('/', name: 'app_contrat_back_index', methods: ['GET'])]
    public function index(Request $request, ContratRepository $contratRepository): Response
{
    // Get the search query from the request
    $searchQuery = $request->query->get('searchQuery');

    // Initialize the variable to hold the filtered contracts
    $contrats = [];

    // If there's a search query, filter contracts based on it
    if ($searchQuery !== null && !empty($searchQuery)) {
        $contrats = $contratRepository->searchContrat($searchQuery);
    } else {
        // If no search query, fetch all contracts
        $contrats = $contratRepository->findAll();
    }

    // Pass the search query and contracts to the template
    return $this->render('contrat_back/index.html.twig', [
        'contrats' => $contrats,
        'searchQuery' => $searchQuery,
    ]);
}
    #[Route('/new', name: 'app_contrat_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(Contrat2Type::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contrat);
            $entityManager->flush();

            return $this->redirectToRoute('app_contrat_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat_back/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_back_show', methods: ['GET'])]
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat_back/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contrat_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Contrat2Type::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contrat_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat_back/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_back_delete', methods: ['POST'])]
    public function delete(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contrat_back_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/generate-pdf', name: 'contrat_generate_pdf')]
public function generatePdf(Contrat $contrat): Response
{
    // Get the HTML content of the page you want to convert to PDF
    $html = $this->renderView('contrat_back/show-pdf.html.twig', [
        // Pass any necessary data to your Twig template
        'contrat' => $contrat,
    ]);

// Configure Dompdf options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);

// Instantiate Dompdf with the configured options
$dompdf = new Dompdf($options);

// Load HTML content into Dompdf
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

    // Set response headers for PDF download
    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="contrat.pdf"');

    return $response;
}
}
