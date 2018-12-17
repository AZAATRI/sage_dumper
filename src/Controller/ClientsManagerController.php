<?php

namespace App\Controller;

use App\Entity\Commercial;
use App\Form\CommercialEditType;
use App\Form\CommercialRegistrationType;
use App\FormEntities\CommercialFormEntity;
use App\Service\SqlServerManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ClientsManagerController extends BaseCommercialController
{
    /**
     * @Route("/clients/list", name="clients_list")
     */
    public function index(SqlServerManager $sqlServerManager) : Response
    {
        $commercial = $this->getCurrentCommercial();
        $clients = $sqlServerManager->getClients($commercial->getCKey());
        return $this->render('clients/clients.html.twig',array('clients'=>$clients));
    }

    /**
     * @Route("/clients/details/{id}", name="clients_details")
     */
    public function details($id,SqlServerManager $sqlServerManager) : Response
    {
        $client = $sqlServerManager->getClientDetails($id);
        return $this->render('clients/details.html.twig',array(
            'client'=>$client
        ));
    }
    /**
     * @Route("/clients/{id}/documents", name="getdocumentsforcurrentclient")
     */
    public function clientDocuments($id,Request $request,SqlServerManager $sqlServerManager) : Response
    {
        $jsonResponse = array('code' => 500,'error' => 'Erreur 500');
        if($request->isXmlHttpRequest()){
            $documents = $sqlServerManager->getDocumentsByClient($id);
            $response = $this->render('clients/clientDocuments.html.twig',array(
                'documents'=>$documents
            ))->getContent();
            $jsonResponse['response'] = $response;
        }
        return new JsonResponse($jsonResponse);
    }
    /**
     * @Route("/clients/{id}/docbyarticles", name="getdocumentsforcurrentclientbyarticles")
     */
    public function clientDocumentsByArticles($id,Request $request,SqlServerManager $sqlServerManager) : Response
    {
        $jsonResponse = array('code' => 500,'error' => 'Erreur 500');
        if($request->isXmlHttpRequest()){
            $documentsByArticles = $sqlServerManager->getDocumentsByClientByArticles($id);
            $response = $this->render('clients/clientDocumentsByArticles.html.twig',array(
                'documentsByArticles'=>$documentsByArticles
            ))->getContent();
            $jsonResponse['response'] = $response;
        }
        return new JsonResponse($jsonResponse);
    }
    /**
     * @Route("/clients/{id}/documentlines", name="getlinedocumentsforcurrentclient")
     */
    public function clientLineDocuments($id,Request $request,SqlServerManager $sqlServerManager) : Response
    {
        $jsonResponse = array('code' => 500,'error' => 'Erreur 500');
        if($request->isXmlHttpRequest()){
            $lineDocuments = $sqlServerManager->getDocumentsByClientByLine($id);
            $response = $this->render('clients/clientLineDocuments.html.twig',array(
                'lines'=>$lineDocuments
            ))->getContent();
            $jsonResponse['response'] = $response;
        }
        return new JsonResponse($jsonResponse);
    }
}
