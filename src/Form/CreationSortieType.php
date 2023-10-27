<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CreationSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>'Nom de la sortie:'])
            ->add('dateHeureDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie:',
                'html5' => true,
                'attr' => [
                    'class' => 'js-datepicker'
                ]
            ])
            ->add('duree', IntegerType::class,[
                'label'=>'Durée:'])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription:',
                'widget' => 'single_text',
            ])
            ->add('nbInscriptionsMax', IntegerType::class,[
                'label'=>'Nombre de places:'])
            ->add('infosSortie', TextareaType::class,[
                'label'=>'Description et infos:'])
            ->add('lieu', EntityType::class, ['class' => Lieu::class,
                'choice_label' => 'nom',
                'required' => true,
                //  'query_builder' => function (EntityRepository $er) {
                //      return $er->createQueryBuilder('Lieu')
                //          ->orderBy('Lieu.nom', 'ASC');
                //  },
                //  'mapped' => false,
                //  'allow_add' => true,
            ])

            //    ->add('longitude',EntityType::class,[
            //        'class'=>Lieu::class,
            //        'choice_label'=>'longitude',
            //       'required' => false,
//
            //    ])
            //    ->add('latitude',EntityType::class,[
            //       'class'=>Lieu::class,
            //       'choice_label'=>'latitude',
            //       'required' => false,

            //  ])
        ;

    }

    public
    function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
