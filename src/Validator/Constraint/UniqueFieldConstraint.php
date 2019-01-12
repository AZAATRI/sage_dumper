<?php

namespace App\Validator\Constraint;
use Symfony\Component\Validator\Constraint;

/**
 * Created by PhpStorm.
 * User: amine
 * Date: 18/12/2018
 * Time: 22:32
 */

/**
 * @Annotation
 */
class UniqueFieldConstraint extends Constraint
{
    public $message = 'Ce "%string%" existe deja';
}