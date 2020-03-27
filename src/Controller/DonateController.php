<?php

namespace App\Controller;

use App\Entity\Recipient;
use App\Entity\Donation;
use App\Form\DonationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/je-fais-un-don")
 */
class DonateController extends AbstractController
{
    /**
     * @Route("/{slug}", name="app.donate.dispatch")
     */
    public function dispatch(Request $request, Recipient $recipient)
    {
        //dump($recipient);

        $donation = new Donation();
        $donation
            ->setRecipient($recipient)
            ->setTypeOfDonations($recipient->getTypeOfDonations())
        ;

        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $donation = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($donation);
            $manager->flush();

            // do anything else you need here, like send an email
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
