<?php

namespace App\Form;

use App\Entity\Collect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt', DateTimeType::class,
            [
                'label' => 'Début de la collecte',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('endAt', DateTimeType::class,
            [
                'label' => 'Fin de la collecte',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('infos')
            ->add('internalDescription')
            //->add('assignedTo')
            //->add('district')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Collect::class,
        ]);
    }
}
