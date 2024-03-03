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
use Symfony\Component\HttpFoundation\JsonResponse;
use TCPDF;
use App\Service\SmsService;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Form\FileType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    
    private $imgDirectory;

    public function __construct(ParameterBagInterface $params)
    {
        // Inject the img_directory parameter
        $this->imgDirectory = $params->get('img_directory');
    }
  
#[Route('/traitement/download-pdf/{id}', name: 'app_traitement_download_pdf', methods: ['GET'])]
public function downloadPdfAction(Request $request, TraitementRepository $traitementRepository, $id): Response
{
    // Retrieve the specific Traitement based on the provided ID
    $traitement = $traitementRepository->find($id);

    if (!$traitement) {
        throw $this->createNotFoundException('Traitement not found');
    }

    // Configure Dompdf according to your needs
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->setIsRemoteEnabled(true);

    // Instantiate Dompdf with our options
    $dompdf = new Dompdf($pdfOptions);

    // Generate the HTML content for the specific Traitement
    $html = $this->renderView('traitement/printtraitement.html.twig', ['traitement' => $traitement]);

    // Load HTML to Dompdf
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait'); // Setup the paper size and orientation
    $dompdf->render(); // Render the HTML as PDF

    $filename = sprintf('traitement-%s.pdf', date('Y-m-d_H-i-s'));

    // Output the generated PDF to Browser (force download)
    return new Response($dompdf->stream($filename, ['Attachment' => true]));
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

   // ... (use statements)

#[Route('/new/{id}', name: 'app_traitement_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, ConstatRepository $repo, SluggerInterface $slugger, TraitementRepository $traitementRepository): Response
{
    $idFromUrl = $request->query->get('id');
    $constat = $repo->find($idFromUrl);

    // Check if the Constat already has a Traitement
    $existingTraitement = $traitementRepository->findOneBy(['identifiant' => $constat]);

    if ($existingTraitement) {
        // You can customize the response as needed, for example, redirect to the Traitement or show an error message
        return $this->redirectToRoute('app_traitement_show', ['id' => $existingTraitement->getId()]);
    }

    $traitement = new Traitement();
    $traitement->setIdentifiant($constat);

    $form = $this->createForm(TraitementType::class, $traitement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload for the logo
        $logoFile = $form->get('photo')->getData();

        if ($logoFile) {
            $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();

            try {
                $logoFile->move(
                    $this->getParameter('img_directory'), // Configure this in your services.yaml
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle the exception if something happens during the file upload
            }

            $traitement->setPhoto($newFilename);
        }

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
