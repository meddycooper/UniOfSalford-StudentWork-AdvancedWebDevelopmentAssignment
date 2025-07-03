<?php

namespace App\Form;

use App\Model\BookSearchCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
class BookSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Title',
            ])
            ->add('author', TextType::class, [
                'required' => false,
                'label' => 'Author',
            ])
            ->add('genre', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Fiction' => 'fiction',
                    'Non-fiction' => 'non-fiction',
                    'Science Fiction' => 'sci-fi',
                    'Fantasy' => 'fantasy',
                    // Add more genres as needed
                ],
                'placeholder' => 'Choose a genre',
                'label' => 'Genre',
            ])
            ->add('rating', NumberType::class, [
                'required' => false,
                'label' => 'Minimum Rating',
                'scale' => 1,
            ])
            ->add('minPages', IntegerType::class, [
                'required' => false,
            ])
            ->add('maxPages', IntegerType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookSearchCriteria::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}