<?php

namespace App\Form;

use App\Entity\Commercial;
use App\Service\SqlServerManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
            ->add('user',RegistrationType::class)
            ->add('lastname',TextType::class,array(
                'required' => true,
                'label'=>'Nom'
            ))
            ->add('firstname',TextType::class,array(
                'required' => true,
                'label'=>'Prenom'
            ))
            ->add('function_name',TextType::class,array(
                'required' => false,
                'label'=>'Fonction'
            ))
            ->add('city',TextType::class,array(
                'required' => false,
                'label'=>'Ville'
            ))
            ->add('phone',TextType::class,array(
                'required' => false,
                'label' => 'Telephone',
            ))
            ->add('email',TextType::class,array(
                'required' => false,
                'label' => 'email',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commercial::class,
        ]);
    }
}
