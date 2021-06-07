<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('roleInProvider', ChoiceType::class, [
                'label' => 'Роль в организации (или системе)',
                'required' => true,
                'mapped' => false,
                'choices' => [
                    'Пользователь организации' => 'ROLE_USER',
                    'Администратор системы' => 'ROLE_ADMIN'
                ],
            ])
            ->add('passwordPlain', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Пароли должны совподать.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'mapped' => false,
                'first_options'  => ['label' => 'Пароль'],
                'second_options' => ['label' => 'Повторить пароль'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true
        ]);
    }
}
