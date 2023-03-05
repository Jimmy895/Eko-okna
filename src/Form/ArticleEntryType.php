<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleEntryType extends AbstractType
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
            ]);

            if ($options['storages']) {
                $builder
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
                        ]
                    ]);
            }

            $builder
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
                'label' => 'Ilość przyjęta:',
                'attr' => [
                    'class' => 'form-control border border-1',
                    'placeholder' => 'Ilość..'
                ],
                'label_attr' => [
                    'class' => 'h5',
                ],
            ])
            ->add('vat', NumberType::class, [
                'required' => true,
                'label' => 'Stawka VAT:',
                'attr' => [
                    'class' => 'form-control border border-1',
                    'placeholder' => 'VAT..'
                ],
                'label_attr' => [
                    'class' => 'h5',
                ],
            ])
            ->add('price', NumberType::class, [
                'required' => true,
                'label' => 'Cena jednostkowa:',
                'attr' => [
                    'class' => 'form-control border border-1',
                    'placeholder' => 'Cena bez podatku..'
                ],
                'label_attr' => [
                    'class' => 'h5',
                ],
            ])
            ->add('attachment', FileType::class, [
                'mapped' => false,
                'multiple' => false,
                'required' => false,
                'label' => 'Faktura *PDF (opcjonalnie):',
                'attr' => [
                    'class' => 'form-control border border-1',
                ],
                'label_attr' => [
                    'class' => 'h5',
                ],
                'constraints' => [

                    new File([
                        'maxSize' => '1024m',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Proszę przesłać plik PDF',
                    ])

                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'article' => null,
            'data_class' => null,
            'storages' => null
        ]);
    }
}
