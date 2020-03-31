<?php

namespace App\Controller;

use App\Repository\ThanksRepository;
use App\Repository\RecipientRepository;
use App\Repository\ReferentRepository;
use App\Repository\TagRepository;
use App\Repository\HomeRepository;
use App\Repository\CollectRepository;
use App\Entity\Tag;
use App\Entity\Contact;
use App\Entity\District;
use App\Form\ContactType;
use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Notifier\Notification\Notification;

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

    /** @var HomeRepository */
    private $homeRepository;

    /** @var CollectRepository */
    private $collectRepository;

    /** @var PaginatorInterface */
    private $paginator;

    public function __construct(
        ThanksRepository $thanksRepository, 
        RecipientRepository $recipientRepository,
        ReferentRepository $referentRepository,
        TagRepository $tagRepository,
        ActivityRepository $activityRepository,
        HomeRepository $homeRepository,
        CollectRepository $collectRepository,
        PaginatorInterface $paginator
    )
    {
        $this->thanksRepository    = $thanksRepository;
        $this->recipientRepository = $recipientRepository;
        $this->referentRepository  = $referentRepository;
        $this->tagRepository       = $tagRepository;
        $this->activityRepository  = $activityRepository;
        $this->homeRepository      = $homeRepository;
        $this->collectRepository   = $collectRepository;
        $this->paginator           = $paginator;
    }

    /**
     * @Route("/", name="app.index")
     */
    public function index()
    {

        
        return 
            $this
                ->render(
                    'index/index.html.twig', 
                    [
                        'thanks' => $this->thanksRepository->findForSliderHome(),
                        'abstract' => $this->homeRepository->findOneBy(['name' => 'abstract']),
                        'collects' => $this->collectRepository->findLastCollect()
                    ]
                )
            ;
    }

    /**
     * @Route("/remerciements", name="app.thanks")
     * @Route("/remerciements/commercant", name="app.thanks.merchant")
     * @Route("/remerciements/habitant", name="app.thanks.citizens")
     */
    public function thanks(Request $request)
    {
        $route = $request->attributes->get('_route');
        switch($route) {
            case 'app.thanks.merchant':
                $qb    = $this->thanksRepository->findMerchantEnabled();
            break; 
            case 'app.thanks.citizens':
                $qb    = $this->thanksRepository->findCitizenEnabled();
            break;
            default:
            $qb    = $this->thanksRepository->findEnabled();
            break;
        }
     
        $thanks = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('index/thanks.html.twig', [
            'thanks' => $thanks,
        ]);
    }

    /**
     * @Route("/referents", name="app.referents")
     */
    public function referents(Request $request)
    {
        $referents = $this->paginator->paginate(
            $this->referentRepository->createQueryBuilder('r')->where('r.isValidated = 1')->getQuery(),
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

        
    /**
     * @Route("/contact", name="app.contact")
     * @Route("/contact/confirmation", name="app.contact.confirmation")
     */
    public function contact(Request $request, NotifierInterface $notifier)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $contact = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($contact);
            $manager->flush();

            $notification = (new Notification('New Invoice'))
            ->content('You got a new invoice for 15 EUR.')
            ->importance(Notification::IMPORTANCE_HIGH);

            $notifier->send($notification, new Recipient('baronsebastien@gmail.com'));
              /*  
            $email = (new NotificationEmail())
                ->from('baronsebastien@gmail.com')
                ->to('baronsebastien@gmail.com')
                ->subject('My first notification email via Symfony')
                ->content(<<<EOF
                    There is a **problem** on your website, you should investigate it
                    right now. Or just wait, the problem might solves itself automatically,
                    we never know.
                    EOF
                )
                ->action('More info?', 'https://example.com/')
                ->importance(NotificationEmail::IMPORTANCE_HIGH)
            ;

            $transport = new EsmtpTransport('smtp.free.fr');
            $mailer = new Mailer($transport);
            $mailer->send($email);*/

            // do anything else you need here, like send an email
            return $this->redirectToRoute('app.contact.confirmation');
        }

        return $this->render('index/contact.html.twig', [
            'form'           => $form->createView(),
            'isConfirmation' => 'app.contact.confirmation' == $request->attributes->get('_route'),
        ]);
    }

    /**
     * @Route("/quartier/{slug}", name="app.district")
     */
    public function district(District $district)
    {
        return $this->render('index/district.html.twig', [
            'district' => $district,
        ]);
    }
}
