<?php

namespace App\Controller;

use App\Entity\Referent;
use App\Form\ProfilFormType;
use App\Form\RegistrationFormType;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
        ): Response {
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
                ->importance('trielsolidarite.org')
                ->action('Plus d\'info ?', 'https://trielsolidarite.org/admin')
            ;
            $mailer->send($notification);

            return $this->redirectToRoute('app.security.register.success');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'registrationForm' => $form->createView()]);
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
                    'form' => $form->createView(),
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

    /**
     * @Route("/mot-de-passe-oublie", name="app.forgotten.password")
     */
    public function forgottenPassword(
    Request $request,
    \Swift_Mailer $mailer,
    TokenGeneratorInterface $tokenGenerator,
    string $notificationFrom
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(Referent::class)->findOneBy(['email' => $email]);
            /* @var $user User */
            if (null === $user) {
                return $this->redirectToRoute('app.index');
            }

            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                return $this->redirectToRoute('app.index');
            }

            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('[trielsolidarite.org] Mot de passe oublié'))
                ->setFrom($notificationFrom)
                // ->setTo($user->getEmail())
                ->setTo('baronsebastien@gmail.com')
                ->setBody(
                    'Voici le lien pour mettre à jour votre nouveau mot de passe : '.$url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgotten_password.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(Referent::class)->findOneBy(['resetToken' => $token]);
            /* @var $user User */

            if (null === $user) {
                $this->addFlash('danger', 'Token Inconnu');

                return $this->redirectToRoute('app.index');
            }

            $user
                ->setResetToken(null)
                ->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')))
            ;

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', ['token' => $token]);
    }
}
