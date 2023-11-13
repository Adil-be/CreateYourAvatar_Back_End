<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\NftCollection;
use App\Entity\NftModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NftModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isCreation = $options['isCreation'];
        $builder
            ->add('name', TextType::class)
            ->add('initialPrice', NumberType::class)
            ->add('quantity', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('featured', CheckboxType::class, [
                'required' => false,
            ])
            ->add('nftCollection', EntityType::class, [
                'class' => NftCollection::class,
                'multiple' => false,
                'by_reference' => true,
                'choice_label' => 'name',
                'label' => 'collection',
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'multiple' => true,
                'by_reference' => true,
                'choice_label' => 'name',
                'label' => 'category',
                'required' => false,
                'expanded' => true
            ])
            ->add('nftImage', NftImageType::class, ['isCreation' => $isCreation])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NftModel::class,
            'isCreation' => false
        ]);
    }
}
