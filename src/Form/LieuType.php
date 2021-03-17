<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, ['label' => 'Lieu : '])
            ->add('adresse', null, ['label' => 'Rue : '])
            ->add('latitude', null, ['label' => 'Latitude : '])
            ->add('longitude', null, ['label' => 'Longitude : '])
            ->add('ville', VilleType::class)
//            ->add('ville', Ville::class, ['choice_label' => 'nom', 'label' => 'Ville '])
//            ->add('ville', null, ['choice_label' => 'code_postal', 'label' => 'Code postal : '])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,


        ]);
    }
}
