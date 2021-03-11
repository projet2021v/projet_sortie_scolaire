<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('date_heure_debut', DateTimeType::class, ['date_widget' => 'single_text', 'time_widget'=>'single_text'])
            ->add('duree')
            ->add('date_limite_inscription', DateType::class, ['widget' => 'single_text'])
            ->add('nb_inscriptions_max')
            ->add('infos_sortie')
            ->add('motif_annulation')
            ->add('lieu', null, ['choice_label'=>'nom'])
            ->add('etat')
            ->add('site', null, ['choice_label'=>'nom'])
            ->add('organisateur', null, ['choice_label'=>'nom'])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
