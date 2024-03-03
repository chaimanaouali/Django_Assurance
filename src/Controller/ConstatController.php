<?php

namespace App\Controller;

use App\Entity\Constat;
use App\Form\ConstatType;
use App\Repository\ConstatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;




#[Route('/constat')]
class ConstatController extends AbstractController
{       
 
    #[Route('/search', name: 'app_constat_search', methods: ['POST'])]
    public function searchAjax(Request $request, ConstatRepository $constatRepository): Response
    {
        $searchTerm = $request->request->get('search');
        $constats = $constatRepository->search($searchTerm);
    
        return $this->render('constat/listAjax.html.twig', [
            'constats' => $constats,
        
        ]);
       
    }
    private $constatRepository;

    public function __construct(ConstatRepository $constatRepository)
    {
        $this->constatRepository = $constatRepository;
    }
    
    #[Route('/download-pdf/{id}', name: 'app_constat_download_pd', methods: ['GET'])]
    public function printPdf(int $id): Response
    {
        // Fetch the Constat entity based on the provided id
        $constat = $this->constatRepository->find($id);
    
        if (!$constat) {
            throw $this->createNotFoundException('Constat not found');
        }
    
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
    
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
    
        // Generate the HTML content for the specific Constat
        $html = $this->renderView('constat/print.html.twig', ['constat' => $constat]);
    
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); // Setup the paper size and orientation
        $dompdf->render(); // Render the HTML as PDF
    
        $filename = sprintf('constat-%s.pdf', date('Y-m-d_H-i-s'));
    
        // Output the generated PDF to Browser (force download)
        return new Response($dompdf->stream($filename, ["Attachment" => true]));
    }
    

#[Route('/', name: 'app_constat_index', methods: ['GET'])]
public function index(Request $request, ConstatRepository $constatRepository): Response
{

    $sort = $request->query->get('sort', 'date');
    $order = $request->query->get('order', 'asc');

    // Toggle the order if the sort field is the same
    if ($sort === $request->query->get('sort')) {
        $order = $order === 'asc' ? 'desc' : 'asc';
    } else {
        // Default to ascending order when changing the sort field
        $order = 'asc';
    }

    $constats = $constatRepository->getConstatsWithSorting($sort, $order);

    return $this->render('constat/index.html.twig', [
        'constats' => $constats,
        'sort' => $sort,
        'order' => $order,
    ]);
}

    #[Route('/new', name: 'app_constat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger ): Response
    {
        $constat = new Constat();
        $form = $this->createForm(ConstatType::class, $constat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

/** @var UploadedFile $imageFile */
$imageFile = $form->get('photo')->getData();
$description = $form->get('description')->getData();

if ($imageFile) {
     $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
     $safeFilename = $slugger->slug($originalFilename);
     $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

    // Move the file to the directory where your images are stored
    try {
        $imageFile->move($this->getParameter('img_directory'), // specify the directory where images should be stored
            $newFilename
        );
     } catch (FileException $e) {
    // Handle the exception if something happens during the file upload
    }

    // Update the 'image' property to store the file name instead of its contents
        $constat->setPhoto($newFilename);
        $constat->setDescription(badwords($description));
}

            $entityManager->persist($constat);
            $entityManager->flush();

            return $this->redirectToRoute('app_constat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('constat/new.html.twig', [
            'constat' => $constat,
            'form' => $form,
        ]);
    }
    #[Route('/new/front', name: 'app_constat_newfront', methods: ['GET', 'POST'])]
    public function newfront(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, ConstatRepository $constatRepository): Response
    {
        $constat = new Constat();
        $form = $this->createForm(ConstatType::class, $constat);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('photo')->getData();
    
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                // Move the file to the directory where your images are stored
                try {
                    $imageFile->move($this->getParameter('img_directory'), $newFilename);
                } catch (FileException $e) {
                    // Handle the exception if something happens during the file upload
                }
    
                // Update the 'image' property to store the file name instead of its contents
                $constat->setPhoto($newFilename);
            }
    
            $entityManager->persist($constat);
            $entityManager->flush();
    
            // Get the iduser from the newly created Constat
            $iduser = $constat->getIduser();
    
            // Redirect to the list_by_user route with the iduser parameter
            return $this->redirectToRoute('list_by_user', ['iduser' => $iduser]);
        }
    
        return $this->renderForm('constat/newfront.html.twig', [
            'constat' => $constat,
            'form' => $form,
        ]);
    }
    #[Route('/list_by_user/{iduser}', name: 'list_by_user', methods: ['GET'])]
    public function listByUser($iduser, ConstatRepository $constatRepository): Response
    {
        // Fetch Constats with the specified iduser from the database
        $constats = $constatRepository->findBy(['iduser' => $iduser]);

        
        return $this->render('constat/list_by_user.html.twig', [
            'constats' => $constats,
            'iduser' => $iduser,
        ]);
    }
    #[Route('/showfront/{id}', name: 'app_constat_showfront', methods: ['GET'])]
    public function showfront(Constat $constat): Response
    {
        return $this->render('constat/showfront.html.twig', [
            'constat' => $constat,
        ]);
    }

    #[Route('/{id}', name: 'app_constat_show', methods: ['GET'])]
    public function show(Constat $constat): Response
    {
        return $this->render('constat/show.html.twig', [
            'constat' => $constat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_constat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Constat $constat, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ConstatType::class, $constat);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload if a new image is provided
            $imageFile = $form->get('photo')->getData();
    
            if ($imageFile) {
                // Similar to the logic in the 'new' action
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                try {
                    $imageFile->move($this->getParameter('img_directory'), $newFilename);
                } catch (FileException $e) {
                    // Handle file upload error
                }
    
                // Update the 'photo' property to store the new file name
                $constat->setPhoto($newFilename);
            }
    
            // Persist the changes and flush the entity
            $entityManager->flush();
    
            return $this->redirectToRoute('app_constat_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('constat/edit.html.twig', [
            'constat' => $constat,
            'form' => $form,
        ]);
    }
    
    
    #[Route('/{id}', name: 'app_constat_delete', methods: ['POST'])]
    public function delete(Request $request, Constat $constat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$constat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($constat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_constat_index', [], Response::HTTP_SEE_OTHER);
    }
    private function handleImageUpload(Request $request, Post $post): void
    {
        $uploadedFile = $request->files->get('post1')['image'];

        if ($uploadedFile) {
            $uploadsDirectory = $this->getParameter('uploads_directory'); // Fetch uploads_directory parameter
            $newFilename = uniqid().'.'.$uploadedFile->guessExtension();

            $uploadedFile->move(
                $uploadsDirectory,
                $newFilename
            );

            $post->setImage($newFilename);
        }
    }
}
function badwords($message){
    $badwords = array("lame","douche","careless","police","amour");
    $filter = array("*","**","****","");
    $message = str_replace($badwords,$filter,$message);
    return $message;

}