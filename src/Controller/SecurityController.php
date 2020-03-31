<?php

namespace App\Controller;

use App\Entity\Referent;
use App\Form\RegistrationFormType;
use App\Form\ProfilFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Mailer\MailerInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(
        Request $request, 
        AuthenticationUtils $authenticationUtils, 
        UserPasswordEncoderInterface $passwordEncoder, 
        MailerInterface $mailer, 
        string $notificationFrom, 
        string $notificationToAdmin
        ): Response
    {
        $user = new Referent();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // register
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user
                ->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                )
            ;

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $notification = (new NotificationEmail())
                ->subject('Nouvelle inscription référent à valider')
                ->to($notificationToAdmin)
                ->from($notificationFrom)
                ->content('Nouvelle inscription référent à valider')
                ->importance('triel-solidarite.org')
                ->action('Plus d\'info ?', 'https://triel-solidarite.org/admin')
            ;
            $mailer->send($notification);

            return $this->redirectToRoute('app.security.register.success');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'registrationForm' => $form->createView(),]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/register/success", name="app.security.register.success")
     */
    public function registerSuccess()
    {
        return 
            $this->render(
                'security/register_succcess.html.twig'
            );
    }

    /**
     * @Route("/profil", name="app.profil")
     */
    public function profil(Request $request)
    {

        $user = $this->getUser();
        $form = $this->createForm(ProfilFormType::class, $user);
        $form->handleRequest($request);

        // register
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app.profil.success');
        }

        return 
            $this->render(
                'security/profil.html.twig',
                [
                    'form' => $form->createView()
                ]
            );
    }

    /**
     * @Route("/profil/success", name="app.profil.success")
     */
    public function profilSuccess()
    {
        return 
            $this->render(
                'security/profil_succcess.html.twig'
            );
    }

}
