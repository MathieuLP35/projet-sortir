<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sites',EntityType::class,[
                'class' => Site::class,
                'choice_label' => 'name_site',
                'required' => false,
                'placeholder' => 'Choisir un site',

            ])
            ->add('event',TextType::class,[
                'label' => 'Le nom de l\'event contient:',
                'required' => false,
            ])
            ->add('startDate', DateTimeType::class,[
                "widget"=>"single_text",
                "label" => "Date de début:",
                'required' => false,
            ])
            ->add('endDate', DateTimeType::class,[
                "widget"=>"single_text",
                "label" => "Date de fin:",
                'required' => false,
            ])
            ->add('organiser', CheckboxType::class, [
                'label'    => 'Organisateur(trice)',
                'required' => false,
            ])
            ->add('isRegistered', CheckboxType::class, [
                'label'    => 'Inscrit(e)',
                'required' => false,
            ])
            ->add('isNotRegistered', CheckboxType::class, [
                'label'    => 'Non inscrit(e)',
                'required' => false,
            ])
            ->add('oldEvent', CheckboxType::class, [
                'label'    => 'Sorties passées',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'p-5 btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
