<?php

namespace App\Form;

use App\Entity\Lieu;
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
        $lieu = new Lieu();
        $builder
            ->add('nom', null, ['label' => 'Nom de la sortie : '])

            ->add('date_heure_debut',
                DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'Date et heure de la sortie : '
                ])

            ->add('date_limite_inscription',
                DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date limite d\'inscription : '
                ])

            ->add('nb_inscriptions_max', null, ['label' => 'Nombre de places : '])

            ->add('duree',null, ['label' => 'Durée : '])

            ->add('infos_sortie', null, ['label' => 'Description et infos : '])

            ->add('site', null, ['choice_label' => 'nom', 'label' => 'Ville organisatrice'])

            ->add('lieu', LieuType::class)


//            ->add('etat')

//            ->add('organisateur', null, ['choice_label'=>'nom'])

           // ->add('motif_annulation')




        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
