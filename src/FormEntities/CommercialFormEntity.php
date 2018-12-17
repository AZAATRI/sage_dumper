<?php

namespace App\FormEntities;

use App\Entity\Commercial;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class CommercialFormEntity
{
    /**
     * @Assert\Length(min="2",minMessage="Votre nom doit faire 6 caracteres")
     *
     */
    public $lastname;

    public $firstname;

    public $function_name;

    public $city;

    public $phone;

    /**
     * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide"
     * )
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Veuillez choisir un commercial")
     */
    public $c_key;

    /**
     * @Assert\Length(min="2",minMessage="Minimum 2 caracteres")
     * @Assert\Length(max="80",maxMessage="Maximum 80 caracteres")
     *
     */
    private $username;

    /**
     * @Assert\Length(min="6",minMessage="Le mot de passe doit faire minimum 6 caracteres")
     */
    private $password;


    private $role_matcher;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRoleMatcher()
    {
        return $this->role_matcher;
    }

    /**
     * @param mixed $role_matcher
     */
    public function setRoleMatcher($role_matcher)
    {
        $this->role_matcher = $role_matcher;
    }

    public function getCommercial():Commercial
    {
        $commercial = new Commercial();
        $commercial->setCity($this->city ?? '')
            ->setCKey($this->c_key ?? '')
            ->setEmail($this->email ?? '')
            ->setFirstname($this->firstname ?? '')
            ->setLastname($this->lastname ?? '')
            ->setFunctionName($this->function_name ?? '')
            ->setCity($this->city ?? '')
            ->setPhone($this->phone ?? '')
            ->setPassword($this->password ?? '')
        ;
        $user = new User();
        $user->setPassword($this->password);
        $user->setRoleMatcher($this->role_matcher);
        $user->setUsername($this->username);
        $commercial->setUser($user);
        return $commercial;
    }



}
