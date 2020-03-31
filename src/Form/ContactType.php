<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Referent;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Prénom / Nom'])
            ->add('email', null, ['label' => 'Votre Email'])
            ->add('description', null, ['label' => 'Description'])
            ->add('subject', null, ['label' => 'Sujet'])
            ->add(
                'referent',
                EntityType::class,
                [
                    'label'    => 'Contacter un référent',
                    'class'    => Referent::class,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return 
                            $er
                                ->createQueryBuilder('r')
                                ->where('r.isValidated = 1')
                                ->orderBy('r.firstname', 'ASC')
                            ;
                    },
                    'choice_label' => function ($referent) {
                        return $referent->getFirstname() . ' ' . $referent->getLastname() .' ( ' . $referent->getDistrict()->getName().' )';
                    },
                    'placeholder' => 'Contacter un référent',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
