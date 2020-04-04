<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Entity\Recipient;
use App\Entity\TypeOfDonation;
use App\Form\DonationType;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

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
        ) {
        $donation = new Donation();
        $donation
            ->setRecipient($recipient)
            ->setTypeOfDonations($recipient->getTypeOfDonations())
        ;

        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donation = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($donation);
            $manager->flush();

            $referent = $donation->getCollect()->getAssignedTo();
            $to = $notificationToAdmin;
            if ($referent && $referent->getEmail()) {
                $to = $referent->getEmail();
            }

            $typeDonations = implode(', ',array_map(
                function(TypeOfDonation $typeOfDonation) {
                    return $typeOfDonation->getName();
                }, 
                $donation->getTypeOfDonations()->toArray()
            ));
            $content = <<<EOC
                Nouvelle donation à collecter \n
                Personne : {$donation->getPerson()}
                Téléphone : {$donation->getPhone()}
                Email : {$donation->getEmail()}
                Adresse : {$donation->getAdress()}
                Infos : {$donation->getAdditionalInfo()}
                Collecte du : {$donation->getCollect()->getStartAt()->format('d-m-Y h:i') } - quartier {$donation->getCollect()->getDistrict()->getName() }
                Beneficiaire : {$donation->getRecipient()->getName() }
                Dons : $typeDonations 
                
            EOC;

            $notification = (new NotificationEmail())
                ->subject('Nouvelle donation à collecter')
                //->to('baronsebastien@gmail.com')
                 ->to($to)
                 ->cc($notificationToAdmin)
                ->from($notificationFrom)
                ->content($content)
                ->importance('trielsolidarite.org')
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
