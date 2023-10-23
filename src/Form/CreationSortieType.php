<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreationSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax')
            ->add('infosSortie')
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
