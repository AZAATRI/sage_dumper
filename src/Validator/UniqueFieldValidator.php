<?php

namespace App\Validator;
use App\Validator\Constraint\UniqueFieldConstraint;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Created by PhpStorm.
 * User: amine
 * Date: 18/12/2018
 * Time: 22:32
 */


class UniqueFieldValidator extends ConstraintValidator
{
    private $em;
/*
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }*/
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueFieldConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueFieldConstraint::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}