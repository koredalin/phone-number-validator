<?php

use Symfony\Component\Validator\ValidatorBuilder;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;

use Symfony\Component\Validator\Constraints\Email;
//use Symfony\Component\Validator\Validator

return [
    ValidatorBuilder::class => DI\create(ValidatorBuilder::class),
    ValidatorInterface::class => function ()
    {
//        return DI\get(ValidatorBuilder::class)->getValidator();
        return Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    },
        
    Email::class => DI\create(Email::class),
];