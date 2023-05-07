<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('body')
//            ->add('author',EntityType::class,[
//                'multiple'=> false,
//                'class' => User::class,
//                'choice_label' => function (?User $user) {
//                    return $user ? strtoupper($user->getUsername()) : '';
//                }
//            ])
            ->add('created_at', DateTimeType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
//                'format' => 'dd-MM-yyyy',
                'data' => new \DateTime(),
                'attr' => ['readonly' => 'readonly']
            ])
            ->add('topic')
            ->add('file', FileType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
