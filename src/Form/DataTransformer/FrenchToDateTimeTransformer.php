<?php

namespace App\Form\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Exception\TransformationFailedException;


class FrenchToDateTimeTransformer implements DataTransformerInterface
{

    public function transform($date)
    {
        if ($date === null) {
            return ' ';
        }
        return $date->format('d/m/Y');
    }
    public function reverseTransform($frenchDate)
    {
        // frenchdate = 20/09/2020
        if ($frenchDate === null) {
            //Execption 
            throw new TransformationFailedException("vous devez fournir une date ! ");
        }
        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        if ($date === false) {
            //Execption 
            throw new TransformationFailedException("le msg de la date n'est pas le bon! ");
        }
        return $date;
    }
}
