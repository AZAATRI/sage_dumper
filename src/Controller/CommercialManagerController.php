<?php

namespace App\Controller;

use App\Entity\Commercial;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CommercialManagerController extends AbstractController
{
    /**
    /**
     * @Route("/admin/clist", name="commercials_list")
     */
    public function index() : Response
    {
        $doctrine = $this->getDoctrine()->getManager();
        $commercials = $doctrine->getRepository(Commercial::class)->findAll();
        return $this->render('commercial_manager/list.html.twig', [
            'commercials' => $commercials
        ]);
    }
}
