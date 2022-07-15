<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //Le builder est ce qui va me permettre de construire le formulaire

        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('birthDate',DateType::class, ['widget' => 'single_text',])
            ->add('deathDate',DateType::class, ['widget' => 'single_text',])
            //J'ajoute le champ book pour gerer la selection d'un
            //livre pour l'auteur
            //je lui met le type " car c'est une relation vers une entité
            //Je met en parametre mon input qui affiche les books
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
