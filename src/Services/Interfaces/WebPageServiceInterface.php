<?php


namespace App\Services\Interfaces;

/**
 *
 * @author Hristo
 */
interface WebPageServiceInterface
{
    public function getErrors(): string;
   
    public function getNextWebPage(): string;
    
    public function isSuccess(): string;
    
    public function getResponseStatus(): int;
}
