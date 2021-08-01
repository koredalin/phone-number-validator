<?php

namespace App\Controllers;

use Twig\Environment;
use Psr\Http\Message\ResponseInterface;
use Twig\Extension\DebugExtension;

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
    
    public function render(string $responseResult, array $arguments): ResponseInterface
    {
        $result = [
            'response' => $responseResult,
            'arguments' => $arguments,
        ];
        $this->response->getBody()->write(
            \json_encode($result)
        );
        
        return $this->response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
