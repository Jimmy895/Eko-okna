<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateNewStorageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nazwa magazynu:',
                'attr' => [
                    'class' => 'form-control border border-1',
                    'placeholder' => 'Nazwa magazynu..'
                ],
                'label_attr' => [
                    'class' => 'h5',
                ],
            ])
            ->add('employee',ChoiceType::class, [
                'multiple' => true,
                'choices' => $options['employee'],
                'required' => true,
                'label' => 'Przypisz pracowników:',
                'row_attr' => [
                    'class' => 'd-flex justify-content-between align-items-center',
                ],
                'attr' => [
                    'class' => 'selectpicker',
                    'multiple' => true,
                    'data-live-search' => true
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
            'employee' => null,
        ]);
    }
}
