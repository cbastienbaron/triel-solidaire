<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Collect;
use App\Entity\Contact;
use App\Entity\District;
use App\Entity\Recipient;
use App\Entity\Referent;
use App\Entity\Tag;
use App\Entity\Thanks;
use App\Form\ContactType;
use App\Repository\ActivityRepository;
use App\Repository\CollectRepository;
use App\Repository\RecipientRepository;
use App\Repository\ReferentRepository;
use App\Repository\TagRepository;
use App\Repository\ThanksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Routing\Annotation\Route;

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
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        $this->thanksRepository = $em->getRepository(Thanks::class); //$thanksRepository;
        $this->recipientRepository = $em->getRepository(Recipient::class); //$recipientRepository;
        $this->referentRepository = $em->getRepository(Referent::class); //$referentRepository;
        $this->tagRepository = $em->getRepository(Tag::class); //$tagRepository;
        $this->activityRepository = $em->getRepository(Activity::class); //$activityRepository;
        $this->collectRepository = $em->getRepository(Collect::class); //$collectRepository;
        $this->paginator = $paginator;
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
                        'thanks' => $this->thanksRepository->findForHome(),
                        'thankMercant' => $this->thanksRepository->findForHome(true, 1),
                        'collects' => $this->collectRepository->findLastCollect(),
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
        switch ($route) {
            case 'app.thanks.merchant':
                $qb = $this->thanksRepository->findMerchantEnabled();
            break;
            case 'app.thanks.citizens':
                $qb = $this->thanksRepository->findCitizenEnabled();
            break;
            default:
            $qb = $this->thanksRepository->findEnabled();
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
     * @Route("/infos-pratiques/{slug}", name="app.activities.by.tag")
     * @Route("/infos-pratiques/", name="app.activities")
     */
    public function activities(Request $request, Tag $tag = null)
    {
        $qb = $this->activityRepository->findAll();
        if ($tag) {
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
                    'currentTag' => $tag,
                    'tags' => $this->tagRepository->findAll(),
                ])
            ;
    }

    /**
     * @Route("/contact", name="app.contact")
     * @Route("/contact/confirmation", name="app.contact.confirmation")
     */
    public function contact(Request $request, MailerInterface $mailer, string $notificationFrom, string $notificationToAdmin)
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

            $notification = (new NotificationEmail())
                ->subject(sprintf('Mail de contact : %s', $contact->getSubject()))
                ->to($notificationToAdmin)
                ->replyTo($contact->getEmail())
                ->from($notificationFrom)
                ->content($contact->getDescription())
                ->importance('trielsolidarite.org')
                ->action('Plus d\'info ?', 'https://trielsolidarite.org/')
            ;

            if ($contact->getReferent()) {
                //$notification->cc($contact->getReferent()->getEmail());
            }

            $mailer->send($notification);

            return $this->redirectToRoute('app.contact.confirmation');
        }

        return $this->render('index/contact.html.twig', [
            'form' => $form->createView(),
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

    /**
     * @Route("/mentions-legales", name="app.legal")
     */
    public function legal()
    {
        return $this->render('index/legal.html.twig');
    }
}
