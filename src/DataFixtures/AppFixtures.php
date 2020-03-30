<?php

namespace App\DataFixtures;

use App\Entity\Recipient;
use App\Entity\TypeOfDonation;
use App\Entity\Referent;
use App\Entity\Donation;
use App\Entity\Tag;
use App\Entity\Thanks;
use App\Entity\Activity;
use App\Entity\Home;
use App\Entity\District;
use App\Entity\Contact;
use App\Entity\Collect;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $careDest = new Recipient;
        $careDest->setName('soignant');

        $manager->persist($careDest);
        
        $vulnerableDest = new Recipient;
        $vulnerableDest->setName('personnes demunies');
        $manager->persist($vulnerableDest);



        $district = new District();
        $district->setName('Toutes zones');


        $districtBeauregard = new District();
        $districtBeauregard->setName('Beauregard');

        $manager->persist($district);
        $manager->persist($districtBeauregard);


        $typeLunchCare = new TypeOfDonation();
        $typeLunchCare
            ->setName('repas midi')
            ->setRecipient($careDest)
        ;

        $typeKeepChildCare = new TypeOfDonation();
        $typeKeepChildCare
            ->setName('garde enfant')
            ->setRecipient($careDest)
        ;

        $manager->persist($typeLunchCare);
        $manager->persist($typeKeepChildCare);


        $typeLunchVulnerable = new TypeOfDonation();
        $typeLunchVulnerable
            ->setName('repas midi')
            ->setRecipient($vulnerableDest)
        ;


        $typeMedicineVulnerable = new TypeOfDonation();
        $typeMedicineVulnerable
            ->setName('aide médicale')
            ->setRecipient($vulnerableDest)
        ;

        $manager->persist($typeLunchVulnerable);
        $manager->persist($typeMedicineVulnerable);

        $i = 0;
        while($i < 20) {

            $referent = new Referent();
            $referent
                ->setEmail('email-'.$i.'@gmail.com')
                ->setLastname('Dupont '.$i)
                ->setFirstname('Jean '.$i)
                ->setPhone('0606060606')
                ->setPassword(
                    $this
                        ->passwordEncoder
                            ->encodePassword(
                                $referent,
                                'password'
                            )
                    )
                ->setIsValidated(true)
                ->setDistrict($districtBeauregard)
            ;
            $manager->persist($referent);
            $i++;
        }


        $tag = new Tag();
        $tag->setName('enfant');
        
        $tagFormation = new Tag();
        $tagFormation->setName('formation gratuite');

        $manager->persist($tag);
        $manager->persist($tagFormation);

        $i = 0;
        while($i < 20) {
            $activityChild = new Activity();
            $activityChild
                ->setName('gully '.$i)
                ->setUrl('https://www.google.com')
                ->setDescription('Offre gratuite ')
                ->addTag($tag)
            ;
    
    
            $activityFormation = new Activity();
            $activityFormation
                ->setName('formation en ligne gratuite '.$i)
                ->setUrl('https://www.google.com')
                ->setDescription('description de la formation')
                //->addTag($tag)
                ->addTag($tagFormation)
            ;
    
            $manager->persist($activityChild);
            $manager->persist($activityFormation);
    
            $i++;
        }
        
        $i = 0;
        while($i < 20) {

            $isMerchant = $i % 2 == 0 ? true : false;
            $thanks = new Thanks();
            $thanks
                ->setIsEnabled(true)
                ->setIsMerchant($isMerchant)
                ->setTitle($isMerchant ? 'titre merci commercant N°'.$i : 'titre merci habitant N°'.$i)
                ->setUrl($isMerchant ? 'https://www.google.com' : 'https://www.facebook.com')
                ->setDescription('description du remerciement')
            ;

            $manager->persist($thanks);
            $i++;        
        }
        
        $home = new Home();
        $home
            ->setName('abstract')
            ->setContent("La crise sanitaire oblige les habitants à rester chez eux, afin de limiter la propagation du virus. 
            Certaines personnes sont en première ligne (notamment le personnel hospitalier) et d'autres sont à protéger en priorité. 
            Plusieurs questions se posent alors:");


        $manager->persist($home);

        $contact = new Contact();
        $contact
            ->setName('nom prenom')
            ->setEmail('toto@gmail.com')
            ->setSubject('sujet')
            ->setDescription('corps du mail')
            ;


        $manager->persist($contact);

        $i = 0;
        while($i < 2) {

            $now = new \DateTime('now');
            $end = clone $now;
            $end->modify('+10 hours');
    
            $collect = new Collect();
            $collect
                ->setStartAt($now)
                ->setEndAt($end)
                ->setDistrict($districtBeauregard)
                ->setAssignedTo($referent)
                ->setInfos('collecte en voiture')
                ->setInternalDescription('donateurs très sympas !')
            ;

            $manager->persist($collect);
            $i++;
        }

         
        $collect = new Collect();
        $collect
            ->setStartAt($now)
            ->setEndAt($end)
            ->setDistrict($district)
            ->setAssignedTo($referent)
            ->setInfos('collecte en voiture')
            ->setInternalDescription('donateurs très sympas !')
        ;

        $manager->persist($collect);



        $donation = new Donation();
        $donation
            ->setPerson('jean michou')
            ->setRecipient($careDest)
            ->setPhone('0606060606')
            ->setEmail('monmain@gmail.com')
            ->setAdditionalInfo('Sonner à l\'interphone')
            ->setAdress('7 rue machin')
            ->addTypeOfDonation($typeLunchCare)
            // ->setAssignedTo($referent)
            // ->setDistrict($districtBeauregard)
            ->setCollect($collect)
        ;

        $donationWithoutAssignement = new Donation();
        $donationWithoutAssignement
            ->setPerson('ac/dc')
            ->setRecipient($careDest)
            ->addTypeOfDonation($typeLunchCare)
            // ->setDistrict($districtBeauregard)
            ->setAdditionalInfo('Sonner à l\'interphone')
            ->setAdress('7 rue machin')

            ->setCollect($collect)
        ;


        $manager->persist($donation);
        $manager->persist($donationWithoutAssignement);

        $manager->flush();
    }
}