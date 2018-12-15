<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 09/12/2018
 * Time: 01:24
 */

namespace App\Service;


use Symfony\Component\Config\Definition\Exception\Exception;

class SqlServerManager
{
    /**
     * @var \PDO
     */
    private $_pdo;

    public function __construct(string $dsn,string $username, string $password){
        try{
            if( !($this->_pdo = new \PDO($dsn, $username, $password))){
               throw new Exception('Problem occured');
            }
        }catch(\Exception $e){
            dump($e->getMessage());die();
        }
    }
    public function getCommercials($ref = null){
        $query = "select CO_No,CO_Nom,CO_Prenom,CO_Fonction,CO_Ville,CO_Service,CO_Telephone,CO_TelPortable,CO_Telecopie,CO_EMail
                    from F_COLLABORATEUR
                    where CO_Vendeur = 1";
        if($ref){
            $query .= "and CO_No = $ref";
        }
        $result = $this->_pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        return $ref ? reset($result) : $result;
    }
}