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
        $form = $this->registrationService->createForm($requestBody);
        if (!$this->registrationService->isValidForm()) {
//            echo __LINE__; exit;
            return $this->render($this->registrationService->getFormErrors(), ['formValidation' => 'failure']);
        }
//        print_r($form); exit;
        return $this->render($form->getRegistrationFormJson(), ['formValidation' => 'success']);
        
//        $phoneConfirmation = $this->registrationService->registrate();
//        if (is_null($phoneConfirmation)) {
//            return $this->render($this->registrationService->getDatabaseErrors(), []);
//        }
        
//        return $this->render(\json_encode($phoneConfirmation), []);
    }
}
