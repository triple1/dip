<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Provider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'label' => 'Имя',
            ])
            ->add('lastName', null, [
                'label' => 'Фамилия'
            ])
            ->add('dob', BirthdayType::class, [
                'widget' => 'single_text',
                'label' => 'Дата рождения',
                'required' => false
            ])
            ->add('address', TextType::class, [
                'label' => 'Адрес',
                'help' => 'for example: Odesa, Malinovskogo str 17, flt. 23',
                'required' => false
            ])
            ->add('phone', null, [
                'label' => 'Телефон',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
            'allow_extra_fields' => true
        ]);
    }
}
