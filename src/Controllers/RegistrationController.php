<?php

namespace App\Controllers;

use App\Controllers\BaseControllerJson;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Validation;
use App\Entities\Forms\Registration as RegistrationForm;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Description of RegistrationFormController
 *
 * @author Hristo
 */
class RegistrationController extends BaseControllerJson
{
    private $validator;
    
    public function __construct(
        ResponseInterface $response
    ) {
        parent::__construct($response);
        $this->validator = Validation::createValidator();
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        
        
        
        $form = new RegistrationForm();
        $errors = $this->validator->validate($form);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return $this->response($errorsString, []);
        }
//        echo ;
        return $this->render(print_r($request->getBody()->getContents(), true), []);
    }
}
