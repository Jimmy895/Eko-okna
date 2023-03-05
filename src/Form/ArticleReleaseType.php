<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleReleaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('article',ChoiceType::class, [

                'choices' => $options['article'],
                'required' => true,
                'label' => 'Nazwa artykułu:',
                'row_attr' => [
                    'class' => 'd-flex flex-column',
                ],
                'attr' => [
                    'class' => 'btn text-left h5 px-3 py-2 bg-white rounded-2 border border-primary',
                ],
                'label_attr' => [
                    'class' => 'h5',
                ],
            ])
            ->add('code', NumberType::class, [
                'required' => true,
                'label' => 'Kod artykułu:',
                'attr' => [
                    'class' => 'form-control border border-1',
                    'placeholder' => 'Kod artykułu..'
                ],
                'label_attr' => [
                    'class' => 'h5',
                ],
            ])
            ->add('amount', NumberType::class, [
                'required' => true,
                'label' => 'Ilość do wydania:',
                'attr' => [
                    'class' => 'form-control border border-1',
                    'placeholder' => 'Ilość..'
                ],
                'label_attr' => [
                    'class' => 'h5',
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'article' => null,
        ]);
    }
}
