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


class CommercialManagerController extends AbstractController
{
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

    /**
     * @Route("/admin/remove/{id}", name="commercials_remove")
     */
    public function delete($id) : Response
    {
        $doctrine = $this->getDoctrine()->getManager();
        $commercial = $doctrine->getRepository(Commercial::class)->find($id);
        $doctrine->remove($commercial);
        $doctrine->flush();
        return $this->redirectToRoute('commercials_list');
    }

    /**
     * @Route("/admin/register", name="registration")
     */
    public function registration(Request $request, ObjectManager $em, UserPasswordEncoderInterface $encoder)
    {
        $commercialForm = new CommercialFormEntity();
        $form = $this->createForm(CommercialRegistrationType::class, $commercialForm);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $commercial = $commercialForm->getCommercial();
            $user = $commercial->getUser();
            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $commercial->setUser($user);
            $em->persist($commercial);
            $em->flush();
            $this->redirectToRoute('registration');
        }
        return $this->render('admin/registration.html.twig',array('form'=>$form->createView()));
    }

}
