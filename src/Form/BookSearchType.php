<?php

namespace App\Form;

use App\Model\BookSearchCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Book Title',
                'attr' => [
                    'placeholder' => 'Enter a title to search...',
                ],
            ])
            ->add('author', TextType::class, [
                'required' => false,
                'label' => 'Author',
                'attr' => [
                    'placeholder' => 'Enter author name...',
                ],
            ])
            ->add('genre', ChoiceType::class, [
                'required' => false,
                'label' => 'Genre',
                'choices' => [
                    'Fiction' => 'fiction',
                    'Non-Fiction' => 'non-fiction',
                    'Science Fiction' => 'sci-fi',
                    'Fantasy' => 'fantasy',
                    'Mystery' => 'mystery',
                    'Biography' => 'biography',
                    'Self-Help' => 'self-help',
                ],
                'placeholder' => 'Select a genre',
            ])
            ->add('rating', NumberType::class, [
                'required' => false,
                'label' => 'Minimum Rating',
                'scale' => 1,
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.5,
                ],
            ])
            ->add('minPages', IntegerType::class, [
                'required' => false,
                'label' => 'Minimum Pages',
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add('maxPages', IntegerType::class, [
                'required' => false,
                'label' => 'Maximum Pages',
                'attr' => [
                    'min' => 0,
                ],
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
