<?php

namespace App\Controller;

use App\Entity\Recipient;
use App\Entity\Donation;
use App\Entity\Collect;
use App\Form\DonationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/mes-collectes")
 */
class CollectController extends AbstractController
{
    /**
     * @Route("/", name="app.collect.index")
     */
    public function index(Request $request, Recipient $recipient)
    {

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
     * @Route("/{id}", name="app.collect.view")
     */
    public function view(Request $request, Collect $collect)
    {
        // check collect user

        return $this->render('donate/success.html.twig', [
            'recipient' => $recipient,
        ]);
    }

    /**
     * @Route("/creation", name="app.collect.create")
     */
    public function create(Request $request)
    {

        // check collect user

        return $this->render('donate/success.html.twig', [
            'recipient' => $recipient,
        ]);
    }
}
