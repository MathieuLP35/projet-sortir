<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Event Name',
            ])
            ->add('startDatetime', DateTimeType::class, [
                'label' => 'Start Date and Time',
            ])
            ->add('duration', null, [
                'label' => 'Duration',
            ])
            ->add('limitRegisterDate', DateTimeType::class, [
                'label' => 'Registration Limit Date',
            ])
            ->add('maxRegisterQty', null, [
                'label' => 'Maximum Registration Quantity',
            ])
            ->add('eventInfos', null, [
                'label' => 'Event Information',
            ])
            ->add('etats', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'id',
                'label' => 'Status',
            ])
            ->add('places', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',
                'label' => 'Place',
                'multiple' => true,
                'expanded' => true, // Si vous voulez permettre la sélection multiple
                'by_reference' => false, // Important pour les relations bidirectionnelles
            ])
            ->add('sites', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'id',
                'label' => 'Site',
            ])
            ->add('isRegister', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'multiple' => true,
                'label' => 'Registered Users',
            ])
            ->add('organiser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'label' => 'Organizer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
