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
                'choice_label' => 'name_site'
            ])
            ->add('event',TextType::class,[
                'label' => 'Le nom de l\'event contient:',
            ])
            ->add('startDate', DateTimeType::class,[
                "widget"=>"single_text",
                'required' => false,
            ])
            ->add('endDate', DateTimeType::class,[
                "widget"=>"single_text",
                'required' => false,
            ])
            ->add('organiser', CheckboxType::class, [
                'label'    => 'Organiser',
                'required' => false,
            ])
            ->add('isRegistered', CheckboxType::class, [
                'label'    => 'Registered',
                'required' => false,
            ])
            ->add('isNotRegistered', CheckboxType::class, [
                'label'    => 'Not registered',
                'required' => false,
            ])
            ->add('oldEvent', CheckboxType::class, [
                'label'    => 'Old Event',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'p-5 bg-primary text-white',
                ]
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
