<?php

namespace App\Form;

use App\Entity\Collect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt')
            ->add('endAt')
            ->add('isCollected')
            ->add('infos')
            ->add('internalDescription')
            ->add('assignedTo')
            ->add('district')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Collect::class,
        ]);
    }
}
