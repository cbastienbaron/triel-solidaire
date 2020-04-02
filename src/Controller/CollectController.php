<?php

namespace App\Controller;

use App\Repository\CollectRepository;
use App\Entity\Recipient;
use App\Entity\Donation;
use App\Entity\Collect;
use App\Form\CollectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/mes-collectes")
 */
class CollectController extends AbstractController
{
    /** @var CollectRepository $collectRepository */
    private $collectRepository;

    public function __construct(CollectRepository $collectRepository)
    {
        $this->collectRepository = $collectRepository;
    }

    /**
     * @Route("/", name="app.collect.index")
     */
    public function index(Request $request)
    {
        $user     = $this->getUser();
        $collects = $this->collectRepository->findBy(['assignedTo' => $this->getUser()], ['startAt' => 'DESC']);

        return $this->render('collect/index.html.twig', [
            'collects' => $collects,
            'user'     => $user,
        ]);
    }


    /**
     * @Route("/collected/{id}", name="app.collect.collected")
     */
    public function collected(Request $request, Collect $collect)
    {
        // check collect user
        if ($collect->getAssignedTo() != $this->getUser()) {
            throw new \RuntimeException('invalid user');
        }
        
        $collect
            ->setIsCollected(true)
            ->setCollectedAt(new \DateTime('now'))
            ;

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($collect);
        $manager->flush();

        return $this->redirectToRoute('app.collect.index');
    }

    /**
     * @Route("/donation/collected/{id}", name="app.collect.donation.collected")
     */
    public function donationCollected(Request $request, Donation $donation)
    {
        // check collect user
        if ($donation->getCollect()->getAssignedTo() != $this->getUser()) {
            throw new \RuntimeException('invalid user');
        }
        
        $donation
            ->setIsCollected(true)
            ->setCollectedAt(new \DateTime('now'))
            ;
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($donation);
        $manager->flush();

        return $this->redirectToRoute('app.collect.index');
    }

    /**
     * @Route("/view/{id}", name="app.collect.view")
     */
    public function view(Request $request, Collect $collect)
    {
        // check collect user
        if ($collect->getAssignedTo() != $this->getUser()) {
            throw new \RuntimeException('invalid user');
        }

        return $this->render('collect/view.html.twig', [
            'collect' => $collect,
            'user'    => $this->getUser(),
        ]);
    }

    /**
     * @Route("/creation", name="app.collect.create")
     * @Route("/creation/success", name="app.collect.create.success")
     */
    public function create(Request $request)
    {
        $isSuccess = 'app.collect.create.success' == $request->attributes->get('_route');
        $user      = $this->getUser();

        $collect = new Collect();
        $collect
            ->setAssignedTo($user)
            ->setDistrict($user->getDistrict())
        ;

        $form = $this->createForm(CollectType::class, $collect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $collect = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($collect);
            $manager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app.collect.create.success');
        }

        return $this->render('collect/create.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'isSuccess' => $isSuccess,
        ]);
    }
}
