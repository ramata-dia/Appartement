<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('desription', TextareaType::class)
            ->add('price')
            ->add('address', TextType::class)
            ->add('coverImage', FileType::class,[
                 'label' => 'CoverImage (PDF file)',
                  'mapped' => false,
                   'required' => false,
                    
            
            ])
            ->add('rooms')
            ->add('isAvailable')
            ->add('createdAt', DateType::class)
            
            ->add('Introduction', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Publier']);
           
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
