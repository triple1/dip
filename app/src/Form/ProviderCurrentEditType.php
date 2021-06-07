<?php

namespace App\Form;

use App\Entity\Provider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProviderCurrentEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Название',
                'help' => 'COVID-19 Help Center'
            ])
            ->add('address', TextareaType::class, [
                'required' => false,
                'help' => 'Studio 103. The Business Centre 61 Wellfield Road Roath Cardiff CF24'
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Контактные емеил',
                'help' => 'john.dou@gmail.com'
            ])
            ->add('phone', null, [
                'label' => 'Контактные телефон',
                'help' => '+380678712345'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Provider::class,
        ]);
    }
}
