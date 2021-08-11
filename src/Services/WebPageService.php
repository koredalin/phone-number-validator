<?php

namespace App\Services;

use App\Services\Interfaces\WebPageServiceInterface;
// Response
use App\Controllers\ResponseStatuses as ResStatus;

/**
 * Description of WebPageService
 *
 * @author Hristo
 */
abstract class WebPageService implements WebPageServiceInterface
{
    protected string $errors;
    
    protected bool $isFinishedServiceAction;
    
    protected string $nextWebPage;
    
    protected bool $isSuccess;
    
    protected int $responseStatus;

    protected function setDefaultWebPageProperties(): void
    {
        $this->errors = '';
        $this->isFinishedServiceAction = false;
        $this->nextWebPage = '';
        $this->isSuccess = false;
        $this->responseStatus = ResStatus::SUCCESS;
    }
    
    /**
     * Returns the errors when a new registration is not recorded into the database.
     * 
     * @return type
     */
    public function getErrors(): string
    {
        return $this->errors;
    }
    
    public function isSuccess(): string
    {
        return $this->isSuccess;
    }
    
    public function getNextWebPage(): string
    {
        if (!$this->isFinishedServiceAction) {
            throw new \Exception('The service action is not finished.');
        }
        
        return $this->nextWebPage;
    }
    
    public function getResponseStatus(): int
    {
        return $this->responseStatus;
    }
}
