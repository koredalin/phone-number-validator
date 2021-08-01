<?php

namespace App\Controllers;

use App\Controllers\BaseControllerJson;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entities\Forms\Registration as RegistrationForm;
use Psr\Http\Message\ServerRequestInterface;
use App\Entities\Country;

/**
 * Description of RegistrationFormController
 *
 * @author Hristo
 */
class RegistrationController extends BaseControllerJson
{
    private ValidatorInterface $validator;
    
    public function __construct(
        ResponseInterface $response
    ) {
        parent::__construct($response);
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $requestBody = \json_decode($request->getBody()->getContents(), true);
        $form = $this->createForm($requestBody);
        $errors = $this->validator->validate($form);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return $this->render($errorsString, []);
        }
        
        
        
        return $this->render(print_r([], true), []);
    }
    
    private function createForm(array $requestBody): RegistrationForm
    {
        $form = new RegistrationForm();
        $form->setEmail($requestBody['email']);
        $phoneNumberInput = (string)trim($requestBody['phoneNumber']);
        $phoneNumberInt = substr($phoneNumberInput, 0, 1) === '0'
            ? (int)Country::BG_PHONE_CODE.substr($phoneNumberInput, 1)
            : (int)$phoneNumberInput;
        $form->setPhoneNumber($phoneNumberInt);
        $form->setPassword($requestBody['password']);
        
        return $form;
    }
}
