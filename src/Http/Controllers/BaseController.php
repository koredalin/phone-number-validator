<?php

namespace App\Http\Controllers;

use Twig\Environment;
use Psr\Http\Message\ResponseInterface;
use Twig\Extension\DebugExtension;

/**
 * Description of BaseController
 *
 * @author Hristo
 */
class BaseController
{
    protected Environment $twig;
    protected ResponseInterface $response;

    public function __construct(Environment $twig, ResponseInterface $response)
    {
        $this->twig = $twig;
        $this->twig->addExtension(new DebugExtension());
        $this->response = $response;
    }
    
    public function render(string $view, array $arguments): ResponseInterface
    {
        $this->response->getBody()->write(
            $this->twig->render($view.'.twig', $arguments)
        );
        
        return $this->response;
    }
}
