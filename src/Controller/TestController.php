<?php

namespace App\Controller;
use App\Service\SqlServerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Created by PhpStorm.
 * User: amine
 * Date: 08/12/2018
 * Time: 00:15
 */
class TestController extends AbstractController
{

    /**
     * @Route("/assettest",name="test_asset")
     */
    public function assettest(){

        return $this->render('base.html.twig');
    }
    /**
     * @Route("/test",name="tests")
     */
    public function testAction(Request $request,SqlServerManager $sqlServerManager){


        die();
        $c = new \PDO("sqlsrv:SERVER=localhost;Database=BIJOU", "sa", "ilis");
        if($c){
            echo "yes";
        }else{
            echo "no";
        }

        $sql = "SELECT * FROM dbo.F_DEPOT";

        foreach($c->query($sql) as $row){
            dump($row);
        }
die();
        $em = $this->getDoctrine()->getManager('sql_server_conn');

        $RAW_QUERY = "SELECT * FROM dbo.F_DEPOT";

        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $result = $statement->fetchAll();
        dump($result);
        die();
    }

}