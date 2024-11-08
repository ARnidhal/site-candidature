<?php

namespace App\Controller;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface; // Import the LoggerInterface

class TesteController extends AbstractController
{
    #[Route('/testmail', name: 'app_test_mail')]
    public function testMail(MailerInterface $mailer, LoggerInterface $logger): Response
    {
        $email = (new Email())
            ->from('hbro6638@gmail.com')
            ->to('nidhal.arfaoui@esprit.com')
            ->subject('Test Email from Symfony')
            ->text('This is a test email.');

        try {
            $mailer->send($email);
            // Log success message
            $logger->info('Email sent successfully to arfaouinidhal77@gmail.com');
            return $this->render('mail/test_mail_success.html.twig');
        } catch (\Exception $e) {
            // Log the error message
            $logger->error('Error sending email: ' . $e->getMessage());
            return new Response('Error sending email: ' . $e->getMessage());
        }
        
    }
}
