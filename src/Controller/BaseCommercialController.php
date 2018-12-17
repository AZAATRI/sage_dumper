<?php

namespace App\Controller;

use App\Entity\Commercial;
use App\Form\CommercialEditType;
use App\Form\CommercialRegistrationType;
use App\FormEntities\CommercialFormEntity;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class BaseCommercialController extends AbstractController
{

    protected function getCurrentCommercial(){
        $repo = $this->getDoctrine()->getRepository(Commercial::class);
        return $repo->findOneBy(array('user' => $this->getUser()->getId()));
    }

}
