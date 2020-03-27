<?php

namespace App\DataFixtures;

use App\Entity\Recipient;
use App\Entity\TypeOfDonation;
use App\Entity\Referent;
use App\Entity\Donation;
use App\Entity\Tag;
use App\Entity\Activity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $careDest = new Recipient;
        $careDest->setName('soignant');

        $manager->persist($careDest);
        
        $vulnerableDest = new Recipient;
        $vulnerableDest->setName('personnes demunies');
        $manager->persist($vulnerableDest);

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
                ->setQuartier('quartier N° '.$i)
            ;
            $manager->persist($referent);
            $i++;
        }

        $donation = new Donation();
        $donation
            ->setPerson('jean michou')
            ->setRecipient($careDest)
            ->addTypeOfDonation($typeLunchCare)
            ->setAssignedTo($referent)
        ;

        $donationWithoutAssignement = new Donation();
        $donationWithoutAssignement
            ->setPerson('ac/dc')
            ->setRecipient($careDest)
            ->addTypeOfDonation($typeLunchCare)
        ;


        $manager->persist($donation);
        $manager->persist($donationWithoutAssignement);

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
                ->setDescription('bablaablbla desription de la formation')
                //->addTag($tag)
                ->addTag($tagFormation)
            ;
    
            $manager->persist($activityChild);
            $manager->persist($activityFormation);
    
            $i++;
        }
        
        $manager->flush();
    }
}