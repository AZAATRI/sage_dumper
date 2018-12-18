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
    public function getClients($commercialRef = null){
        $query = "select CT_Num Code_Client,CT_Intitule,CT_Contact,CT_Adresse,CT_Complement,CT_CodePostal,CT_Ville,
                  CT_CodeRegion,CT_Pays,CT_Identifiant,CT_Encours,CT_Assurance,CT_Telephone,CT_Telecopie,CT_EMail,CT_Site
                    from F_COMPTET F left join F_COLLABORATEUR C on F.CO_No = C.CO_No
                    where CT_Type = 0 and F.CO_No = $commercialRef";
        $query = $this->_pdo->query($query);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    public function getClientDetails($clientRef){
        $query = "select CT_Num Code_Client,CT_Intitule,CT_Contact,CT_Adresse,CT_Complement,CT_CodePostal,CT_Ville,
                  CT_CodeRegion,CT_Pays,CT_Identifiant,CT_Encours,CT_Assurance,CT_Telephone,CT_Telecopie,CT_EMail,CT_Site
                    from F_COMPTET F left join F_COLLABORATEUR C on F.CO_No = C.CO_No
                    where CT_Type = 0 and CT_Num = '$clientRef'";
        $query = $this->_pdo->query($query);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return reset($result);
    }
    public function getDocumentsByClient($ref){
        $query = "select E.DO_Type,E.DO_Piece,E.DO_Date,E.DO_Ref Ref_Document,E.DO_Tiers,C.CT_Intitule,E.CO_No,Col.CO_Nom,Col.CO_Prenom,E.DE_No,F_depot.DE_Intitule,Sum(DL_Qte) Qte,Sum(DL_PrixUnitaire) PU,Sum(DL_MontantHT) MontantHT,Sum(DL_MontantTTC) MontantTTC
from F_DOCLIGNE L 
inner join F_DOCENTETE E on L.DO_Piece = E.DO_Piece and L.DO_Domaine = E.DO_Domaine and L.DO_Type = E.DO_Type
inner join F_COMPTET C on C.CT_Num = E.DO_Tiers
left join F_COLLABORATEUR COL on COL.CO_No = E.CO_No
left join F_depot on F_depot.DE_No = E.DE_No
where E.DO_Domaine = 0 and C.CT_Num = '$ref' 
group by E.DO_Type,E.DO_Piece,E.DO_Date,E.DO_Ref,E.DO_Tiers,C.CT_Intitule,E.CO_No,Col.CO_Nom,Col.CO_Prenom,E.DE_No,F_depot.DE_Intitule
order by E.DO_Piece
";
        $query = $this->_pdo->query($query);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    public function getDocumentsByClientByArticles($ref){
            $query = "select E.DO_Type,E.DO_Piece,E.DO_Date,E.DO_Ref Ref_Document,E.DO_Tiers,C.CT_Intitule,E.CO_No,Col.CO_Nom,Col.CO_Prenom,E.DE_No,F_depot.DE_Intitule,L.AR_Ref,DL_Design,Sum(DL_Qte) Qte,Sum(DL_PrixUnitaire) PU,Sum(DL_MontantHT) MontantHT,Sum(DL_MontantTTC) MontantTTC
    from F_DOCLIGNE L 
    inner join F_DOCENTETE E on L.DO_Piece = E.DO_Piece and L.DO_Domaine = E.DO_Domaine and L.DO_Type = E.DO_Type
    inner join F_COMPTET C on C.CT_Num = E.DO_Tiers
    left join F_COLLABORATEUR COL on COL.CO_No = E.CO_No
    left join F_depot on F_depot.DE_No = E.DE_No
    where E.DO_Domaine = 0 and C.CT_Num = '$ref' 
    group by E.DO_Type,E.DO_Piece,E.DO_Date,E.DO_Ref,E.DO_Tiers,C.CT_Intitule,E.CO_No,Col.CO_Nom,Col.CO_Prenom,E.DE_No,F_depot.DE_Intitule,L.AR_Ref,DL_Design
    order by E.DO_Piece";
        $query = $this->_pdo->query($query);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDocumentsByClientByLine($ref){
        $query = "select E.DO_Type,E.DO_Piece,E.DO_Date,E.DO_Ref Ref_Document,E.DO_Tiers,C.CT_Intitule,E.CO_No,Col.CO_Nom,Col.CO_Prenom,E.DE_No,F_depot.DE_Intitule,L.AR_Ref,DL_Design,DL_Qte,DL_PrixUnitaire,DL_MontantHT,DL_MontantTTC
from F_DOCLIGNE L 
inner join F_DOCENTETE E on L.DO_Piece = E.DO_Piece and L.DO_Domaine = E.DO_Domaine and L.DO_Type = E.DO_Type
inner join F_COMPTET C on C.CT_Num = E.DO_Tiers
left join F_COLLABORATEUR COL on COL.CO_No = E.CO_No
left join F_depot on F_depot.DE_No = E.DE_No
where E.DO_Domaine = 0 and C.CT_Num = '$ref' 
order by E.DO_Piece";
        $query = $this->_pdo->query($query);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    public function getDocumentsByArticleByLine($ref, $commercialRef){
        $query = "select E.DO_Type,E.DO_Piece,E.DO_Date,E.DO_Ref Ref_Document,E.DO_Tiers,C.CT_Intitule,C.CT_Num,E.CO_No,Col.CO_Nom,Col.CO_Prenom,E.DE_No,F_depot.DE_Intitule,L.AR_Ref,DL_Design,DL_Qte,DL_PrixUnitaire,DL_MontantHT,DL_MontantTTC
from F_DOCLIGNE L 
inner join F_DOCENTETE E on L.DO_Piece = E.DO_Piece and L.DO_Domaine = E.DO_Domaine and L.DO_Type = E.DO_Type
inner join F_COMPTET C on C.CT_Num = E.DO_Tiers
left join F_COLLABORATEUR COL on COL.CO_No = E.CO_No
left join F_depot on F_depot.DE_No = E.DE_No
where E.DO_Domaine = 0 and L.AR_Ref = '$ref' and COL.CO_No = '$commercialRef'
order by E.DO_Piece";
        $query = $this->_pdo->query($query);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function getArticles(){
        $query = "select A.AR_Ref,A.AR_Design,A.FA_CodeFamille,F.FA_Intitule,AR_PrixVen,AR_CodeBarre,AR_Pays
from F_ARTICLE A inner join F_FAMILLE F on F.FA_CodeFamille = A.FA_CodeFamille";
        $query = $this->_pdo->query($query);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    public function getStockArticles($fetchStyle = \PDO::FETCH_ASSOC){
        $query = "Select AR_Ref,A.DE_No,D.DE_Intitule,sum(AS_QteSto) Qte_Stock,SUM(AS_MontSto) Montant_Stock
from F_ARTSTOCK A inner join F_DEPOT D on A.DE_No = D.DE_No
group by AR_Ref,A.DE_No,D.DE_Intitule
order by AR_Ref";
        $query = $this->_pdo->query($query);
        $result = $query->fetchAll($fetchStyle);
        return $result;
    }
    public function getCurrentArticle($ref){
        $query = "select A.AR_Ref,A.AR_Design,A.FA_CodeFamille,F.FA_Intitule,AR_PrixVen,AR_CodeBarre,AR_Pays
from F_ARTICLE A inner join F_FAMILLE F on F.FA_CodeFamille = A.FA_CodeFamille where A.AR_Ref = '$ref'";
        $query = $this->_pdo->query($query);
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }
    public function getStockByArticle($ref){
        $query = "Select AR_Ref,A.DE_No,D.DE_Intitule,D.DE_Adresse,D.DE_Ville,sum(AS_QteSto) Qte_Stock,SUM(AS_MontSto) Montant_Stock
from F_ARTSTOCK A inner join F_DEPOT D on A.DE_No = D.DE_No
where AR_Ref = '$ref'
group by AR_Ref,A.DE_No,D.DE_Intitule,D.DE_Adresse,D.DE_Ville
order by AR_Ref";
        $query = $this->_pdo->query($query);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
}