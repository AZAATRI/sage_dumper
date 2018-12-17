<?php

namespace App\Form;

use App\Entity\Commercial;
use App\FormEntities\CommercialFormEntity;
use App\Service\SqlServerManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommercialRegistrationType extends AbstractType
{
    /**
     * @var SqlServerManager
     */
    private $_sqlServerManager;
    public function __construct(SqlServerManager $sqlServerManager)
    {
        $this->_sqlServerManager = $sqlServerManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $commercials = $this->_sqlServerManager->getCommercials();
        $structure = array();
        $structure['Choisissez un commercial'] = '';
        foreach ($commercials as $commercial){
            $structure[$commercial['CO_Nom']] = $commercial['CO_No'];
        }
        $builder
            ->add('c_key',ChoiceType::class,array(
                'choices'=>$structure,
                'label'=>'Reference Commercial'
            ))
            ->add('username',TextType::class,array(
                'required' => true,
                'label'=>'Login',
                'attr' => [
                    'placeholder' => 'Saisissez le login du commercial'
                ]
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
            ->add('role_matcher',HiddenType::class, array(
                'data' => 'commercial'
            ))
            ->add('lastname',TextType::class,array(
                'required' => true,
                'label'=>'Nom',
                'attr' => [
                    'placeholder' => 'Saisissez le nom du commercial'
                ]
            ))
            ->add('firstname',TextType::class,array(
                'required' => true,
                'label'=>'Prenom',
                'attr' => [
                    'placeholder' => 'Saisissez le prénom du commercial'
                ]
            ))
            ->add('function_name',TextType::class,array(
                'required' => false,
                'label'=>'Fonction',
                'attr' => [
                    'placeholder' => 'Saisissez la fonction du commercial'
                ]
            ))
            ->add('city',TextType::class,array(
                'required' => false,
                'label'=>'Ville',
                'attr' => [
                    'placeholder' => 'Saisissez la ville du commercial'
                ]
            ))
            ->add('phone',TextType::class,array(
                'required' => false,
                'label' => 'Telephone',
                'attr' => [
                    'placeholder' => 'Saisissez le telephone du commercial'
                ]
            ))
            ->add('email',TextType::class,array(
                'required' => false,
                'label' => 'email',
                'attr' => [
                    'placeholder' => 'Saisissez l\'adresse email du commercial'
                ]
            ))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommercialFormEntity::class,
        ]);
    }
}
