<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $event = $options['event'];

        $builder
            ->add('eventName', TextType::class, [
                'data' => $event->getName(),
                'disabled' => true,
                'label' => 'Nom de la sortie',
            ])
            ->add('eventDate', DateTimeType::class, [
                'data' => $event->getStartDatetime(),
                "widget"=>"single_text",
                'disabled' => true,
                'label' => 'Date de la sortie',
            ])

            // Ajoutez d'autres champs et configurez-les en fonction de vos besoins
            ->add('cancellationReason', TextareaType::class, [
                'label' => 'Motif d\'annulation',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);

        $resolver->setRequired(['event']);
        $resolver->setAllowedTypes('event', Event::class);
    }
}
