<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 16/12/2018
 * Time: 13:45
 */

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DocumentTypeExtention extends AbstractExtension
{
    const _TYPES = [
                        0 => 'Devis',
                        1 => 'Bon de commande',
                        2 => 'Préparation de livraison ',
                        3 => 'Bon de livraison',
                        4 => 'Bon de retour',
                        5 => 'Bon d’avoir ',
                        6 => 'Facture',
                        7 => 'Facture comptabilisée',
                     ];
    public function getFilters()
    {
        return array(
            new TwigFilter('document_type', array($this, 'documentType')),
        );
    }

    public function documentType($index)
    {
        return isset(self::_TYPES[$index]) ? self::_TYPES[$index] : 'Type inconnu';
    }
}