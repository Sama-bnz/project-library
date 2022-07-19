<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title')
            ->add('nbPages')
            ->add('publishedAt',DateType::class, ['widget' => 'single_text',])
            ->add('author',EntityType::class,['class' => Author::class,'choice_label' => 'last_name',])
            ->add('author',EntityType::class,[
                'class' => Author::class,
                'choice_label' => function ($author) {
                //Je donne à ma creéation d'article la possibilité d'avoi 2 données au lieu d'une
                    return $author->getfirstName() . ' / ' . $author->getlastName();
                },
                'placeholder' =>'Choisissez votre auteur',
            ])

            ->add('image', FileType::class,[
                'mapped' => false
            ])

            ->add('submit', SubmitType::class);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
