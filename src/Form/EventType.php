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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom de la sortie',
                'required' => true
            ])
            ->add('startDatetime', DateTimeType::class,[
                "widget"=>"single_text",
                'label' => 'Date et heure de la sortie',
                'required' => true
            ])
            ->add('duration', IntegerType::class,[
                'label' => 'Durée (en minutes)',
            ])
            ->add('limitRegisterDate', DateTimeType::class,[
                "widget"=>"single_text",
                'label' => 'Date limite d\'inscription',
                'required' => true
            ])
            ->add('maxRegisterQty', IntegerType::class,[
                'label' => 'Nombre de places',
                'required' => true
            ])
            ->add('eventInfos', TextareaType::class,[
                'label' => 'Description et infos',
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',
                'label' => 'Lieu',
                'required' => true
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nameSite',
                'label' => 'Site',
                'required' => true
            ])
            // ->add('organiser', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'name',
            //     'required' => true
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
