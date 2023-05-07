<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price_range',ChoiceType::class,[
                'choices' => [
                    '1-500' => '1-500',
                    '501-1000' => '501-1000',
                    '1001- 10000' => '1001- 10000',
                ],
                'multiple' => 'true',
                'expanded'=>true,
                'required' => false
            ])
            ->add('category', EntityType::class,[
                'class'=> Category::class,
                'multiple' => 'true',
                'expanded'=>true,
//                'label_attr' => ['class' => 'checkbox-inline'],
                'required' => false
            ])
            ->add('Filter',SubmitType::class)

        ;
    }

//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        dd($resolver);
//        $resolver->setDefaults([
//            'data_class' => Category::class,
//
//        ]);
//    }
}
