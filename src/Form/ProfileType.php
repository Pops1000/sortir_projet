<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'attr' => ['placeholder' => 'pseudo'],
                'required' => true
            ])
            ->add('prenom', TextType::class,[
                'required'=> true
            ])
            ->add('nom',TextType::class,[
                'required'=> true
            ])
            ->add('telephone',TextType::class,[
                'required'=> true
            ])
            ->add('mail',TextType::class,[
                'required'=> true
            ])
            ->add('motPasse',TextType::class,[
                'required'=> true,
                'label'=>'Mot de passe'
            ])
            ->add('campus', TextType::class,[
                'disabled'=>true,
                'required'=>true
            ])
/*TODO utiliser les données campus dans une liste deroulante*/

            //  ->add('campus', EntityType::class, [
            //      'class' => Campus::class,
            //      'choice_label' => 'nom',
            //     'required' => true,
            //        ])
            //  ->add('maPhoto')
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
