<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
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
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control border border-1',
                    'autocomplete' => 'new-password'
                ],
                'label_attr' => [
                    'class' => 'h5',
                ],

                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę wprowadzić hasło.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Twoje hasło powinno zawierać co najmniej {{ limit }} znaków.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
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
            'data_class' => User::class,
        ]);
    }
}
