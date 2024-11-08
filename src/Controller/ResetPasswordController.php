<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ResetPasswordRequestType;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResetPasswordController extends AbstractController
{
    private ResetPasswordHelperInterface $resetPasswordHelper;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    #[Route('/reset-password', name: 'app_forgot_password_request')]
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $requestForm = $this->createForm(ResetPasswordRequestType::class);
        $requestForm->handleRequest($request);

        if ($requestForm->isSubmitted() && $requestForm->isValid()) {
            $data = $requestForm->getData();
            $email = $data['email'];

            // Rechercher l'utilisateur par son email
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                // Générer un token de réinitialisation de mot de passe
                try {
                    $resetToken = $this->resetPasswordHelper->generateResetToken($user);
                } catch (ResetPasswordExceptionInterface $e) {
                    $this->addFlash('error', 'Unable to generate reset token. Please try again later.');
                    return $this->redirectToRoute('app_login');
                }

                // Envoyer l'email de réinitialisation
                $emailMessage = (new Email())
                    ->from('ghazouani4444@gmail.com') // Replace with your email
                    ->to($user->getEmail())
                    ->subject('Réinitialisez votre mot de passe')
                    ->html($this->renderView('emails/reset_password.html.twig', [
                        'resetToken' => $resetToken,
                    ]));

                // Send email and handle potential errors
                try {
                    $mailer->send($emailMessage);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'There was a problem sending the email: ' . $e->getMessage());
                    return $this->redirectToRoute('app_forgot_password_request');
                }

                // Rediriger vers une page de confirmation
                return $this->redirectToRoute('app_check_email');
            } else {
                $this->addFlash('error', 'No account found with that email address.');
            }
        }

        // Afficher le formulaire de réinitialisation
        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $requestForm->createView(),
        ]);
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function reset(Request $request, string $token = null): Response
    {
        if ($token) {
            // Vérifier et utiliser le token pour permettre la réinitialisation
        }

        // Afficher le formulaire de réinitialisation
        return $this->render('reset_password/reset.html.twig', [
            'token' => $token,
        ]);
    }

    #[Route('/check-email', name: 'app_check_email')]
    public function checkEmail(): Response
    {
        return $this->render('reset_password/check_email.html.twig');
    }
}
