<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\EventListener\MessageListener;
use Twig\Environment as TwigEnvironment;
use Symfony\Component\Mailer\MailerInterface;
use App\Mailer\TransportConfigurator;

class EmailController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function sendSimpleEmail(MailerInterface $mailer): Response
    {
        // Configuration du transport SMTP à l'aide de TransportConfigurator
        $transport = TransportConfigurator::configureSmtpTransport();

        // Création de l'instance du Mailer
        $mailer = new Mailer($transport);

        // Création de l'objet Email
        $email = (new Email())
            ->from('amaranour025@gmail.com')
            ->to('amaranour025@gmail.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        // Envoi de l'e-mail
        $mailer->send($email);

        return new Response('Email sent successfully!');
    }
}

