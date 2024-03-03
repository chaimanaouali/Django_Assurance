<?php

namespace App\Controller;

use App\Form\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;


class EmailType extends AbstractController
{
    public function sendEmail(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(EmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->from('amaranour025@gmail.com')
                ->to($data['recipient'])
                ->subject($data['subject'])
                ->text($data['message']);

            $mailer->send($email);

            return $this->redirectToRoute('email_success');
        }

        return $this->render('email/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}