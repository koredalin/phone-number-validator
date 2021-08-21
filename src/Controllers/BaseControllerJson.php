<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use App\Controllers\ResponseStatuses as ResStatus;
use App\Entities\Transaction;

/**
 * Description of BaseControllerJson
 *
 * @author Hristo
 */
class BaseControllerJson
{
    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }
    
    public function render(array $responseResult, array $arguments, int $status = ResStatus::SUCCESS): ResponseInterface
    {
        $result = [
            'response' => $responseResult,
            'arguments' => $arguments,
        ];
        $this->response->getBody()->write(
            \json_encode($result)
        );
        
        return $this->response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
    
    protected function getRestrictedEmailAndPhoneNumber(Transaction $transaction): array
    {
        $email = $transaction->getUser()->getEmail();
        $result['email'] = '***'.substr($email, -(int)(strlen($email) / 2));
        $phoneNumber = $transaction->getPhone()->getPhoneNumber();
        $result['phoneNumber'] = '***'.substr($phoneNumber, -(int)(strlen($phoneNumber) / 2));
            
        return $result;
    }
}
