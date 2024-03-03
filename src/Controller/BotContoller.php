<?php

namespace App\Controller;

use Sun\Contract\Country;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/bot')]
class BotController extends AbstractController
{
    #[Route('/rendezvous/bot', name: 'app_bot')]
    public function index(Request $request): Response
    {
    $qa = [
        'Bonjour' => 'Bonjour ! Comment puis-je vous aider ?',
        'quand je peux avoir mon devis ?' => 'Merci de remplir vos informations dans la devis pour plus de détails',
        'je peux avoir une version pdf ?' => 'Bien sûr, vous pouvez télécharger la version PDF du devis <a href="' . $this->generateUrl('app_devis_index') . '">ici</a>.',
        'oooo'=>'Je ne comprends pas ce que vous voulez dire!',
        'J ai besoin de l aide' =>'Oui, comment puis-je vous aider ?'

           ];
    $message = $request->request->get('message');
    if (array_key_exists($message, $qa)) {
        $response = $qa[$message];
    } else {
        $response = 'SALUT';
    }
    return $this->render('bot/index.html.twig', [
        'response' => $response
    ]);

}}