<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Response\QrCodeResponse;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Alignment\LabelAlignmentLeft;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Writer\PngWriter;



#[Route('/devis')]
class DevisController extends AbstractController
{
    #[Route('/', name: 'app_devis_index', methods: ['GET'])]
public function index(DevisRepository $devisRepository, Request $request ,EntityManagerInterface $entityManager): Response
{
    $searchQuery = $request->query->get('q');

    $sortBy = $request->query->get('sort', 'puissance'); // Default sorting by 'puissance'
    $order = $request->query->get('order', 'ASC'); // Default order ascending

    // Create a query builder
    $queryBuilder = $devisRepository->createQueryBuilder('d');

    // Apply sorting
    $queryBuilder->orderBy('d.'.$sortBy, $order);

    // If there's a search query, filter devis based on it
    if ($searchQuery) {
        $queryBuilder
            ->andWhere('d.modele LIKE :searchQueryModel OR d.nom LIKE :searchQueryNom OR d.id = :searchQueryId')
            ->setParameter('searchQueryModel', '%'.$searchQuery.'%')
            ->setParameter('searchQueryNom', '%'.$searchQuery.'%')
            ->setParameter('searchQueryId', $searchQuery);
    }
    
    

    // Execute the query
    $devis = $queryBuilder->getQuery()->getResult();

    $modeleStatistics = $entityManager->createQueryBuilder()
        ->select('d.modele AS category, COUNT(d.id) AS devisCount')
        ->from(Devis::class, 'd')
        ->groupBy('d.modele')
        ->getQuery()
        ->getResult();

    return $this->render('devis/index.html.twig', [
        'devis' => $devis,
        'sortBy' => $sortBy,
        'order' => $order,
        'searchQuery' => $searchQuery, // Pass the search query to the template
    ]);
}


    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $devi = new Devis();
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($devi);
            $entityManager->flush();

            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_show', methods: ['GET'])]
    public function show(Devis $devi): Response
    {
        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_delete', methods: ['POST'])]
    public function delete(Request $request, Devis $devi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($devi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/generate-pdf', name: 'devi_generate_pdf')]
public function generatePdf(Devis $devi): Response
{
    // Get the HTML content of the page you want to convert to PDF
    $html = $this->renderView('devis/show-pdf.html.twig', [
        // Pass any necessary data to your Twig template
        'devi' => $devi,
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
    $response->headers->set('Content-Disposition', 'attachment; filename="devis.pdf"');

    return $response;
}
  



}

