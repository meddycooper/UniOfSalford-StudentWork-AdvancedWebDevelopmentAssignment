<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('coverImageFile', FileType::class, [
                'label' => 'Book Cover Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG/PNG)',
                    ])
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Book Title',
            ])
            ->add('author', TextType::class, [
                'label' => 'Author Name',
            ])
            ->add('genre', TextType::class, [
                'label' => 'Genre',
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Summary',
            ])
            ->add('pages', TextType::class, [
                'label' => 'Pages',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Book',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
