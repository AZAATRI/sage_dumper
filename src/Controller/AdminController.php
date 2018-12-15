<?php

namespace App\Controller;

use App\Entity\Commercial;
use App\Form\CommercialRegistrationType;
use App\Service\SqlServerManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="admin_index")
     */
    public function index()
    {

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/register", name="registration")
     */
    public function registration(Request $request, ObjectManager $em, UserPasswordEncoderInterface $encoder)
    {
        $commercial = new Commercial();
        $form = $this->createForm(CommercialRegistrationType::class, $commercial);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
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

    /**
     * @Route("/commercials", name="comemrcials_list")
     */
    public function commercialsAction(){

        return array();
    }


    /**
     * @Route("/getcommercialdata", name="comemrcial_data")
     */
    public function getcommercialdata(Request $request, SqlServerManager $sqlServerManager)
    {
        $result = array('code'=>404,'message'=>'error');
        if($request->isXmlHttpRequest()){
            $commercial = $sqlServerManager->getCommercials($request->get('reference'));
            $result = array('code'=>200,'message'=>'success','data'=>$commercial);
        }
        return new Response(json_encode($result),$result['code']);
    }

}
