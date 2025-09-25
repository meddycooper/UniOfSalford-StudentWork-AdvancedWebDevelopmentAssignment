<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Positive;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('coverImageFile', FileType::class, [
                'label' => 'Book Cover Image',
                'mapped' => false,
                'required' => false,
                'help' => 'Optional. Upload a JPEG or PNG image (max 2MB).',
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG/PNG).',
                    ]),
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Book Title',
                'attr' => [
                    'placeholder' => 'Enter the book title',
                ],
            ])
            ->add('author', TextType::class, [
                'label' => 'Author Name',
                'attr' => [
                    'placeholder' => 'Enter author name',
                ],
            ])
            ->add('genre', TextType::class, [
                'label' => 'Genre',
                'attr' => [
                    'placeholder' => 'e.g., Fiction, Fantasy, Sci-Fi',
                ],
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Summary',
                'attr' => [
                    'placeholder' => 'Write a brief summary of the book',
                    'rows' => 5,
                ],
            ])
            ->add('pages', IntegerType::class, [
                'label' => 'Number of Pages',
                'constraints' => [
                    new Positive([
                        'message' => 'The number of pages must be a positive integer.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Enter total pages',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Book',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
