<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startDatetime')
            ->add('duration')
            ->add('limitRegisterDate')
            ->add('maxRegisterQty')
            ->add('eventInfos')
            ->add('etats', EntityType::class, [
                'class' => Etat::class,
'choice_label' => 'id',
            ])
            ->add('places', EntityType::class, [
                'class' => Place::class,
'choice_label' => 'id',
            ])
            ->add('sites', EntityType::class, [
                'class' => Site::class,
'choice_label' => 'id',
            ])
            ->add('isRegister', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('organiser', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
