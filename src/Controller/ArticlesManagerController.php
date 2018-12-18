<?php

namespace App\Controller;

use App\Entity\Commercial;
use App\Form\CommercialEditType;
use App\Form\CommercialRegistrationType;
use App\FormEntities\CommercialFormEntity;
use App\Service\SqlServerManager;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ArticlesManagerController extends BaseCommercialController
{

    /**
     * @Route("/articles/list", name="articles_list")
     */
    public function index(SqlServerManager $sqlServerManager) : Response
    {
        $articles = $sqlServerManager->getArticles();
        $articles = $this->matchWithStockAndSlugify($sqlServerManager,$articles);
        return $this->render('articles/list.html.twig',array('articles'=>$articles));
    }
    /**
     * @Route("/articles/{ref}/details", name="article_details")
     */
    public function details($ref,SqlServerManager $sqlServerManager) : Response
    {
        $ref = base64_decode($ref);
        $article = $sqlServerManager->getCurrentArticle($ref);
        $article['slug'] = base64_encode($article['AR_Ref']);
        return $this->render('articles/details.html.twig',array('article'=>$article));
    }
    private function matchWithStockAndSlugify(SqlServerManager $sqlServerManager,$articles){
        $stocks = $sqlServerManager->getStockArticles(\PDO::FETCH_GROUP|\PDO::FETCH_ASSOC);
        foreach ($articles as &$article){
            $article['slug'] = base64_encode($article['AR_Ref']);
            $article['stock'] = 0;
            if(isset($stocks[$article['AR_Ref']]) && is_array($stocks[$article['AR_Ref']])){
                foreach ($stocks[$article['AR_Ref']] as $depot){
                    $article['stock'] += (int) $depot['Qte_Stock'];
                }
            }
        }
        return $articles;
    }

    /**
     * @Route("/article/{slug}/articlesDepot", name="getarticlesbydepot")
     */
    public function stockArticlesByDepot($slug,Request $request,SqlServerManager $sqlServerManager) : Response
    {
        $jsonResponse = array('code' => 500,'error' => 'Erreur 500');
        if($request->isXmlHttpRequest()){
            $id = base64_decode($slug);
            $depots = $sqlServerManager->getStockByArticle($id);
            $response = $this->render('articles/articlesDepot.html.twig',array(
                'depots'=>$depots
            ))->getContent();
            $jsonResponse['response'] = $response;
        }
        return new JsonResponse($jsonResponse);
    }
    /**
     * @Route("/article/{slug}/documentlines", name="getlinedocumentsforcurrentarticle")
     */
    public function clientLineDocuments($slug,Request $request,SqlServerManager $sqlServerManager) : Response
    {
        $jsonResponse = array('code' => 500,'error' => 'Erreur 500');
        $commercialRef = $this->getCurrentCommercial()->getCKey();
        if($request->isXmlHttpRequest()){
            $id = base64_decode($slug);
            $lineDocuments = $sqlServerManager->getDocumentsByArticleByLine($id, $commercialRef);
            $lineDocuments = $this->addStockValueColumn($sqlServerManager,$lineDocuments);
            $response = $this->render('articles/articleLineDocuments.html.twig',array(
                'lines'=>$lineDocuments
            ))->getContent();
            $jsonResponse['response'] = $response;
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
