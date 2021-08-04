<?php

namespace App\Controllers;

use App\Controllers\BaseControllerJson;
use Psr\Http\Message\ResponseInterface;
use App\Services\Interfaces\RegistrationServiceInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Description of RegistrationFormController
 *
 * @author Hristo
 */
class RegistrationController extends BaseControllerJson
{
    private RegistrationServiceInterface $registrationService;
    
    public function __construct(
        ResponseInterface $response,
        RegistrationServiceInterface $registrationService
    ) {
        parent::__construct($response);
        $this->registrationService = $registrationService;
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $requestBody = $request->getBody()->getContents();
        $this->registrationService->createForm($requestBody);
        if (!$this->registrationService->isValidForm()) {
//            echo __LINE__; exit;
            return $this->render($this->registrationService->getFormErrors(), ['formValidation' => 'failure']);
        }
//        print_r($form); exit;
//        return $this->render($form->getRegistrationFormJson(), ['formValidation' => 'success']);
        
        $phoneConfirmation = $this->registrationService->registrate();
        if (is_null($phoneConfirmation)) {
//            echo __LINE__; exit;
            return $this->render($this->registrationService->getDatabaseErrors(), ['databaseValidation' => 'failure']);
        }
//            echo __LINE__; exit;
        
        return $this->render(\json_encode($phoneConfirmation), ['formValidation' => 'success', 'databaseValidation' => 'success',]);
    }
}
