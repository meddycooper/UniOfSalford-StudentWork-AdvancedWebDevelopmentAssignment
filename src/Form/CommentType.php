<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('content', TextareaType::class, [
            'label' => 'Comment',
            'attr' => [
                'rows' => 3,
                'placeholder' => 'Write your comment here',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Removed reference to specific entity to make this generic and public-friendly
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
