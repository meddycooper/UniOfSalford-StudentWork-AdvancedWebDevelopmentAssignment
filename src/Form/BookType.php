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
                'label' => 'Cover Image',
                'mapped' => false,
                'required' => false,
                'help' => 'Optional. Upload a JPEG or PNG image (max 2MB).',
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG/PNG).',
                    ]),
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => ['placeholder' => 'Book title'],
            ])
            ->add('author', TextType::class, [
                'label' => 'Author',
                'attr' => ['placeholder' => 'Author name'],
            ])
            ->add('genre', TextType::class, [
                'label' => 'Genre',
                'attr' => ['placeholder' => 'Book genre'],
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Summary',
                'attr' => [
                    'placeholder' => 'Brief summary of the book',
                    'rows' => 5,
                ],
            ])
            ->add('pages', IntegerType::class, [
                'label' => 'Pages',
                'constraints' => [
                    new Positive(['message' => 'Pages must be a positive number.']),
                ],
                'attr' => ['placeholder' => 'Number of pages'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
