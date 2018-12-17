<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, array(
                'mapped' => false
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Veuillez ressaisir le mot de passe',
                'first_options'  => array('label' => 'Mot de passe','attr' => [
                    'placeholder' => 'Saisissez le mot de passe du commercial'
                ]),
                'second_options' => array('label' => 'Confirmation du mot de passe','attr' => [
                    'placeholder' => 'Confirmez le mot de passe'
                ]),
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Changer mon mot de passe',
                'attr' => array(
                    'class' => 'btn btn-primary btn-block'
                )
            ))
        ;
    }

}
