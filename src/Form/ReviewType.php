<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('review_text', TextareaType::class, [
                'label' => 'Review Text',
                'attr' => [
                    'placeholder' => 'Write your review here...',
                    'rows' => 5,
                ],
            ])
            ->add('rating', ChoiceType::class, [
                'label' => 'Rating (1-5)',
                'choices' => [
                    '1 star' => 1,
                    '2 stars' => 2,
                    '3 stars' => 3,
                    '4 stars' => 4,
                    '5 stars' => 5,
                ],
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'notInRangeMessage' => 'Please select a rating between {{ min }} and {{ max }} stars.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit Review',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Safe for public sharing, no entity tied
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
