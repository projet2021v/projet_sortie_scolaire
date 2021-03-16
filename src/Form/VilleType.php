<?php

namespace App\Form;

use App\Entity\Ville;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // ->add('nom', null, ['label' => 'Nom de la ville : '])
//            ->add('nom', ChoiceType::class, [
//            'choice_name' => ChoiceList::fieldName($this,'nom'),'label' => 'Ville : '
//        ])
            //            ->add('nom', null, ['choice_label'=> ChoiceList::label($this, 'nom')])
            ->add('nom', EntityType::class, [
                'class'=> Ville::class,
                'choice_label' => 'nom',
                'label' => 'Ville : '
            ])
            ->add('code_postal', null, ['label' => 'Code postal : '])
        ;
    }

//    public function buildForm(FormBuilderInterface $builder, array $options)
//    {
////        $villeLieu = $this->getDoctrine()->getManager()->getRepository('Ville')->find();
//        $builder
//           // ->add('nom', null, ['label' => 'Nom de la ville : '])
//            ->add('ville_nom', ChoiceType::class, [
////                'class' => 'Ville',
//               'choices' => 'nom',
//               ])
//
//            ->add('code_postal', null, ['label' => 'Code postal : '])
//        ;
//    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
