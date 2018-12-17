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
