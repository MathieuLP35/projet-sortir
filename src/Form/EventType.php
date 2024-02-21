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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Required;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'required' => true
            ])
            ->add('startDatetime', DateTimeType::class,[
                "widget"=>"single_text",
                'required' => true
            ])
            ->add('duration', IntegerType::class)
            ->add('limitRegisterDate', DateTimeType::class,[
                "widget"=>"single_text",
                'required' => true
            ])
            ->add('maxRegisterQty', IntegerType::class,[
                'required' => true
            ])
            ->add('eventInfos', TextareaType::class)
            // ->add('etats', EntityType::class, [
            //     'class' => Etat::class,
            //     'choice_label' => 'libelle',
            //     'required' => true
            // ])
            ->add('places', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('sites', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nameSite',
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
