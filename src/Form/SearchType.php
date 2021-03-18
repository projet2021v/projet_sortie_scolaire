<?php


namespace App\Form;


use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
       ->add('mot_cle', TextType::class, [
                  'label' => false,
                  'required' => false,
                  'attr' => [
                      'placeholder' => 'Rechercher'
          ]
       ])
       ->add('date_min', DateType::class, [
                   'label' => 'Entre ',
                   'required' => false,
                   'widget' => 'single_text'
       ])
       ->add('date_max', DateType::class, [
                   'label' => ' et ',
                   'required' => false,
                   'widget' => 'single_text'
       ])
       ->add('orga', CheckboxType::class, [
                   'label' => 'Sorties dont je suis l\'organisateur/trice',
                   'required' => false
       ])
        ->add('inscrit', CheckboxType::class, [
                   'label' => 'Sorties auxquelles je suis inscrit/e',
                   'attr' => [
                       'id' => 'chk_inscrit'
                   ],
                   'required' => false
               ])
        ->add('non_inscrit', CheckboxType::class, [
                   'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                    'attr' => [
                        'id' => 'chk_non_inscrit'
                    ],
                   'required' => false
               ])
        ->add('passee', CheckboxType::class, [
                   'label' => 'Sorties passées',
                   'required' => false
               ])

       ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
         $resolver->setDefaults([
             'data_class' => SearchData::class,
             'method' => 'GET',
             'CSRF_protection' => false
         ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

}