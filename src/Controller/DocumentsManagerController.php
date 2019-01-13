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


class DocumentsManagerController extends BaseCommercialController
{

    /**
     * @Route("/documents", name="documents_details")
     */
    public function details() : Response
    {
        return $this->render('documents/details.html.twig');
    }
    /**
     * @Route("/commercial/documents", name="getdocumentsforcurrentcommercial")
     */
    public function commercialDocuments(Request $request,SqlServerManager $sqlServerManager) : Response
    {
        $jsonResponse = array('code' => 500,'error' => 'Erreur 500');
        if($request->isXmlHttpRequest()){
            $documents = $sqlServerManager->getDocumentsByCommercial($this->getCurrentCommercial()->getCKey());
            $response = $this->render('documents/commercialDocuments.html.twig',array(
                'documents'=>$documents
            ))->getContent();
            $jsonResponse['response'] = $response;
        }
        return new JsonResponse($jsonResponse);
    }
    /**
     * @Route("/commercial/docbyarticles", name="getdocumentsforcurrentcommercialbyarticles")
     */
    public function commercialDocumentsByArticles(Request $request,SqlServerManager $sqlServerManager) : Response
    {
        $jsonResponse = array('code' => 500,'error' => 'Erreur 500');
        if($request->isXmlHttpRequest()){
            $documentsByArticles = $sqlServerManager->getDocumentsByCommercialByArticles($this->getCurrentCommercial()->getCKey());
            $documentsByArticles = $this->addStockValueColumn($sqlServerManager,$documentsByArticles);
            $response = $this->render('documents/commercialDocumentsByArticles.html.twig',array(
                'documentsByArticles'=>$documentsByArticles
            ))->getContent();
            $jsonResponse['response'] = $response;
        }
        return new JsonResponse($jsonResponse);
    }
    /**
     * @Route("/commercial/documentlines", name="getlinedocumentsforcurrentcommercial")
     */
    public function commercialLineDocuments(Request $request,SqlServerManager $sqlServerManager) : Response
    {
        $jsonResponse = array('code' => 500,'error' => 'Erreur 500');
        if($request->isXmlHttpRequest()){
            $lineDocuments = $sqlServerManager->getDocumentsByCommercialByLine($this->getCurrentCommercial()->getCKey());
            $lineDocuments = $this->addStockValueColumn($sqlServerManager,$lineDocuments);
            $response = $this->render('documents/commercialLineDocuments.html.twig',array(
                'lines'=>$lineDocuments
            ))->getContent();
            $jsonResponse['response'] = $response;
        }
        return new JsonResponse($jsonResponse);
    }
    /**
     * @Route("/commercial/un_invoices", name="getunregulatedinvoicesdocs")
     */
    public function commercialUnregulatedInvoices(Request $request,SqlServerManager $sqlServerManager) : Response
    {
        $jsonResponse = array('code' => 500);
        if($request->isXmlHttpRequest()){
            $invoices = $sqlServerManager->getUnregulatedInvoicesByCommercial($this->getCurrentCommercial()->getCKey());
            $response = $this->render('clients/unregulatedInvoices.html.twig',array(
                'invoices'=>$invoices
            ))->getContent();
            $jsonResponse['response'] = $response;
            $jsonResponse['code'] = 200;
        }
        return new JsonResponse($jsonResponse);
    }
    private function addStockValueColumn(SqlServerManager $sqlServerManager,$dataWithArticlesAndDepot = array()){
        $stocks = $sqlServerManager->getStockArticles();
        $indexedStocks = array();
        foreach ($stocks as $s){
            $indexedStocks[$s['AR_Ref']][$s['DE_No']] = $s;
        }
        foreach ($dataWithArticlesAndDepot  as $key=>$data){
            $dataWithArticlesAndDepot[$key]['Qte_Stock'] = isset($indexedStocks[$data['AR_Ref']][$data['DE_No']])
                ? (int) $indexedStocks[$data['AR_Ref']][$data['DE_No']]['Qte_Stock'] : 0;
        }
        return $dataWithArticlesAndDepot;
    }
}
