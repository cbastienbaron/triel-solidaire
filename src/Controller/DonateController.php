<?php

namespace App\Controller;

use App\Entity\Recipient;
use App\Entity\Donation;
use App\Form\DonationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Mailer\MailerInterface;

/**
 * @Route("/je-fais-un-don")
 */
class DonateController extends AbstractController
{
    /**
     * @Route("/{slug}", name="app.donate.dispatch")
     */
    public function dispatch(
        Request $request, 
        Recipient $recipient,
        MailerInterface $mailer, 
        string $notificationFrom, 
        string $notificationToAdmin
        )
    {
        $donation = new Donation();
        $donation
            ->setRecipient($recipient)
            ->setTypeOfDonations($recipient->getTypeOfDonations())
        ;

        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donation = $form->getData();
            $manager  = $this->getDoctrine()->getManager();
            $manager->persist($donation);
            $manager->flush();

            $referent = $donation->getCollect()->getAssignedTo();
            $to       = $notificationToAdmin;
            if($referent && $referent->getEmail()) {
                $to = $referent->getEmail();
            }

            $notification = (new NotificationEmail())
                ->subject('Nouvelle donation à collecter')
                ->to($to)
                ->cc($notificationToAdmin)
                ->from($notificationFrom)
                ->content('Nouvelle donation à collecter')
                ->importance('triel-solidarite.org')
                ->action('Plus d\'info ?', $this->generateUrl('app.collect.index'))
            ;
            $mailer->send($notification);

            return $this->redirectToRoute('app.donate.success', ['slug' => $recipient->getSlug()]);
        }

        return $this->render('donate/dispatch.html.twig', [
            'donationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/merci", name="app.donate.success")
     */
    public function success(Request $request, Recipient $recipient)
    {
        return $this->render('donate/success.html.twig', [
            'recipient' => $recipient,
        ]);
    }

}
