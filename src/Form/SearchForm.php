<?php
namespace App\Form;

use App\Data\SearchData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType {
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);

    }

    public function getBlockPrefix()
    {
        return'';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class,[
                'label' => 'Le nom de la sortie contient:',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('campus', EntityType::class, [
                    'label' => 'Campus:',
                    'required' => false,
                    'class' => Campus::class,
                    'choice_label' => 'nom',
                    'multiple' => false
                ]
            )
            ->add('dateDebut', DateType::class, [
                'label' => 'Entre:',
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'js-datepicker'
                ]
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Et:',
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'js-datepicker'
                ]
            ])
            ->add('isOrganisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
            ->add('isInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false
            ])
            ->add('isNotInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false
            ])
            ->add('isPassees', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false
            ]);


    }

}