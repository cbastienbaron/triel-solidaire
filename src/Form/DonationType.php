<?php

namespace App\Form;

use App\Entity\Donation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\TypeOfDonation;
use Doctrine\ORM\EntityRepository;

class DonationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Donation $donation */
        $donation = $options['data'];

        $builder
            ->add(
                'person',
                TextType::class,
                [
                    'label'   => 'Qui suis-je ? (nom, prénom)',
                    'required' => true,
                ]
            )

            ->add(
                'phone',
                TelType::class,
                [
                    'label'   => 'Téléphone',
                    'required' => false,
                ]
            )

            ->add(
                'quartier',
                TextType::class,
                [
                    'label'   => 'Nom de votre quartier',
                    'required' => false,
                ]
            )

            ->add(
                'email',
                TextType::class,
                [
                    'label'   => 'E-mail',
                    'required' => false,
                ]
            )

            ->add(
                'adress',
                TextType::class,
                [
                    'label'   => 'Adresse',
                    'required' => false,
                ]
            )

            ->add(
                'additionalInfo',
                TextareaType::class,
                [
                    'label'   => 'Information complémentaire',
                    'required' => false,
                ]
            )

            ->add(
                'donateAt',
                DateTimeType::class,
                [
                    'label'   => 'Date souhaitée pour le ramassage de votre don',
                    'required' => false,
                    'widget' => 'single_text'
                ]
            )

            ->add('typeOfDonations', EntityType::class, [
                'label'   => 'Type de dons',
                'class' => TypeOfDonation::class,
                'choices' => $donation->getTypeOfDonations(),
                'choice_label' => 'name',
                'expanded'  => true,
                'multiple'  => true,
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Donation::class]);
    }
}
