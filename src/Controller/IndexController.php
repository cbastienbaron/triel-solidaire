<?php

namespace App\Controller;

use App\Repository\ThanksRepository;
use App\Repository\RecipientRepository;
use App\Repository\ReferentRepository;
use App\Repository\TagRepository;
use App\Entity\Tag;
use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{

    /** @var ThanksRepository */
    private $thanksRepository;
    
    /** @var RecipientRepository */
    private $recipientRepository;

    /** @var ReferentRepository */
    private $referentRepository;

    /** @var TagRepository */
    private $tagRepository;

    /** @var ActivityRepository */
    private $activityRepository;

    /** @var PaginatorInterface */
    private $paginator;

    public function __construct(
        ThanksRepository $thanksRepository, 
        RecipientRepository $recipientRepository,
        ReferentRepository $referentRepository,
        TagRepository $tagRepository,
        ActivityRepository $activityRepository,
        PaginatorInterface $paginator
    )
    {
        $this->thanksRepository    = $thanksRepository;
        $this->recipientRepository = $recipientRepository;
        $this->referentRepository  = $referentRepository;
        $this->tagRepository       = $tagRepository;
        $this->activityRepository  = $activityRepository;
        $this->paginator           = $paginator;
    }

    /**
     * @Route("/", name="app.index")
     */
    public function index()
    {
        return $this->render('index/index.html.twig', [
            'thanks' => $this->thanksRepository->findBy([], ['createdAt' => 'desc'], 10),
        ]);
    }

    /**
     * @Route("/remerciements", name="app.thanks")
     */
    public function thanks()
    {
        return $this->render('index/thanks.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/referents", name="app.referents")
     */
    public function referents(Request $request)
    {
        $referents = $this->paginator->paginate(
            $this->referentRepository->createQueryBuilder('r')->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('index/referents.html.twig', [
            'referents' => $referents,
        ]);
    }

    
    /**
     * @Route("/je-fais-un-don", name="app.donate")
     */
    public function donate()
    {
        return $this->render('index/donate.html.twig', [
            'recipients' => $this->recipientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/activites/{slug}", name="app.activities.by.tag")
     * @Route("/activites/", name="app.activities")
     */
    public function activities(Request $request, Tag $tag = null)
    {
        $qb = $this->activityRepository->findAll();
        if($tag) {
            $qb = $this->activityRepository->findByTag($tag);
        }

        $activities = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            10
        );

        return 
            $this
                ->render('index/activities.html.twig', [
                    'activities' => $activities,
                    'tags'       => $this->tagRepository->findAll(),
                ])
            ;
    }
}
