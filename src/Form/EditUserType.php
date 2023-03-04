<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class, [
                'required' => true,
                'label' => 'Login:',
                'attr' => [
                    'class' => 'form-control border border-1',
                ],
                'label_attr' => [
                    'class' => 'h5',
                ],
            ])
            ->add('storage_list_id', ChoiceType::class, [
                'choices'  => $options['storages'],
                'label' => 'Przypisz magazyn:',
                'row_attr' => [
                    'class' => 'd-flex justify-content-between align-items-center',
                ],
                'attr' => [
                    'class' => 'btn h5 px-3 py-2 bg-white rounded-2 border border-primary',
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
            'storages' => null,
        ]);
    }
}
