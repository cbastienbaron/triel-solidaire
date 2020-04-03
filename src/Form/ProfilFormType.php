<?php

namespace App\Form;

use App\Entity\District;
use App\Entity\Referent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstname',
                TextType::class,
                [
                    'label' => 'Prénom',
                    'required' => true,
                ]
            )

            ->add(
                'lastname',
                TextType::class,
                [
                    'label' => 'Nom',
                    'required' => true,
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Votre email',
                    'required' => true,
                ]
            )

            ->add(
                'phone',
                TelType::class,
                [
                    'label' => 'Telephone',
                    'required' => false,
                ]
            )

            ->add(
                'district',
                EntityType::class,
                [
                    'label' => 'Votre Quartier',
                    'class' => District::class,
                    'choice_label' => 'name',
                ]
            )

            ->add(
                'address',
                EmailType::class,
                [
                    'label' => 'Adresse',
                    'required' => false,
                ]
            )

            ->add(
                'infos',
                TextareaType::class,
                [
                    'label' => 'Informations complémentaire',
                    'required' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Referent::class,
        ]);
    }
}
