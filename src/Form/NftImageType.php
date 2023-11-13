<?php

namespace App\Form;

use App\Entity\NftImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class NftImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isCreation = $options['isCreation'];
        if ($isCreation) {
            $builder
                ->add('file', VichImageType::class, [
                    'required' => true,
                    'allow_delete' => false,
                    'delete_label' => 'delete',
                    'download_uri' => true,
                    'image_uri' => true,
                    'asset_helper' => true,
                    'imagine_pattern' => 'my_thumb',

                ]);
        } else {
            $builder
                ->add('file', VichImageType::class, [
                    'required' => false,
                    'allow_delete' => false,
                    'delete_label' => 'delete',
                    'download_uri' => true,
                    'image_uri' => true,
                    'asset_helper' => true,
                    'imagine_pattern' => 'my_thumb',

                ]);

        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NftImage::class,
            'isCreation' => false
        ]);
    }
}
