<?php

namespace App\Form;


use App\Entity\Nft;

use App\Entity\NftModel;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('buyingPrice', NumberType::class)
            ->add('sellingPrice', NumberType::class)
            ->add('token', TextType::class)
            ->add('inSale', CheckboxType::class, [
                'label' => 'En vente',
                'required' => false,
            ])

            ->add('featured', CheckboxType::class, [
                'label' => 'featured',
                'required' => false,
            ])
            ->add('nftModel', EntityType::class, [
                'class' => NftModel::class,
                'multiple' => false,
                'choice_label' => 'name',
                'label' => 'Model NFT',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'multiple' => false,
                'choice_label' => 'email',
                'label' => 'Owner',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nft::class,
            'isCreation' => false
        ]);
    }
}
