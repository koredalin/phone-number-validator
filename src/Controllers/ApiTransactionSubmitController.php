<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use App\Controllers\ResponseStatuses as ResStatus;
use App\Controllers\Response\Models\TransactionSubmitResult;

/**
 * Description of BaseControllerJson
 *
 * @author Hristo
 */
class ApiTransactionSubmitController
{
    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }
    
    public function render(TransactionSubmitResult $responseResult, array $arguments, int $status = ResStatus::SUCCESS): ResponseInterface
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
}
