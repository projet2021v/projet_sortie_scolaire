<?php


namespace App\Form;


use App\Data\CreerSortieData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie : '
            ])

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

//            ->add('site', null, ['choice_label' => 'nom', 'label' => 'Ville organisatrice'])



            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreerSortieData::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}