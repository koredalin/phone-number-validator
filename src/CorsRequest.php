<?php

namespace App;

use Psr\Http\Server\MiddlewareInterface;
use League\Route\Router;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of CorsRequest
 *
 * @author Hristo
 */
class CorsRequest implements MiddlewareInterface
{
    private static $alwaysAllowedHeaders = [
        'http://localhost:4200'
    ];
    
    private Router $router;
    
    public function __construct(
        Router $router
    ) {
        $this->router = $router;
    }
        
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Find allowed verbs/methods for the requested router and add them to the response header
        $verbs = $this->router->findVerbsForRequest($request->getPath());
        $response = $response->withHeader("Access-Control-Allow-Methods", implode(", ", $verbs));

        // Send the request headers back to the client as allowed (avoids pre-flight fail) + some extras
        $headers  = array_merge(array_keys(getallheaders()), self::$alwaysAllowedHeaders);
        $response = $response->withHeader("Access-Control-Allow-Headers", strtolower(implode(", ", $headers)));

        return $response;
    }
}