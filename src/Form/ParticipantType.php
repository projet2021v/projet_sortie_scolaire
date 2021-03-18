<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, ['label'=>'Pseudo : '])
//            ->add('roles')
            ->add('prenom', null, ['label'=>'Prénom : '])
            ->add('nom', null, ['label'=>'Nom : '])
            ->add('telephone', null, ['label'=>'Téléphone : '])
            ->add('mail', null, ['label'=>'Email : '])
            ->add('password',
                RepeatedType::class, [
                    'type'=>PasswordType::class,
                    'invalid_message' => 'Les deux champs doivent correspondre',
                    'required' => true,
                    'first_options' => ['label'=>'Mot de passe : '],
                    'second_options' => ['label'=>'Confirmation : '],
                ])
//            ->add('administrateur')
//            ->add('actif')
            ->add('site', null, ['choice_label'=>'nom', 'label'=>'Ville de rattachement : '])
            ->add('url_photo', null, ['label'=>'Ma photo : '])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
