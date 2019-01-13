<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 16/12/2018
 * Time: 13:45
 */

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PasswordExtention extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('password_hide', array($this, 'passwordHidden')),
        );
    }

    public function passwordHidden($password)
    {
        /*
        if($password){
            $size = strlen($password) / 2;
            $password = str_repeat('*',$size).substr($password,$size);
        }else{
            $password = '******';
        }*/
        return $password;
    }
}