<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Candidature;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin')
            ->add('nomprenom')
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,    // Utiliser le sélecteur de date HTML5
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'dateNaissancePicker',
                ],
                'format' => 'yyyy-MM-dd',   // Assurez-vous que le format est correct
                'data' => new \DateTime(), // Définir la date actuelle par défaut
            ])
            ->add('adresse', null, [
                'label' => 'ADRESSE',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('anneMaitrise', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,    // Utiliser le sélecteur de date HTML5
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'anneMaitrisePicker',
                ],
                'format' => 'yyyy-MM-dd',   // Assurez-vous que le format est correct
                'data' => new \DateTime(), // Définir la date actuelle par défaut
            ])
            ->add('moyenne')
            ->add('nbranneexper')
            ->add('diplome')
            ->add('specialite')
            ->add('universite')
            ->add('fichier', FileType::class, [
                'label' => 'service Picture',
                'mapped' => false,
                'required' => false, // Set to true if the photo is mandatory
                // Add any other options you need, such as validation constraints
            ])
            ->add('email')
            ->add('numtel')
            ->add('save',SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'data_class' => Candidature::class,
        ]);
        
    }
}
