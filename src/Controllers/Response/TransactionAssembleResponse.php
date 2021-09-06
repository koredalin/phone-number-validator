<?php

namespace App\Controllers\Response;

use App\Controllers\Response\Interfaces\ResponseAssembleInterface;
use App\Controllers\Response\Models\TransactionSubmit as TransactionResponse;
use App\Entities\Transaction;

/**
 * Description of TransactionAssembleResponse
 *
 * @author Hristo
 */
class TransactionAssembleResponse implements ResponseAssembleInterface
{
    private ?TransactionResponse $response;
    
    public function __construct(?TransactionResponse $response = null)
    {
        $this->response = is_null($response) ? TransactionResponse::generateAnEmptyObject() : $response;
    }
    
    
    public function assembleResponse(
        ?Transaction $transaction,
        bool $isRestrictedInfo = true,
        ?bool $isSuccess = null,
        string $nextWebPage = '',
        string $error = ''
    ): TransactionResponse {
        if (is_null($transaction)) {
            $this->response = $this->resetResponse();
            return $this->response;
        }
        
        $this->response->transactionId = $transaction->getId();
        $this->response->email = $isRestrictedInfo ? $this->getRestrictedEmail($transaction) : $transaction->getUser()->getEmail();
        $this->response->phoneCode = $isRestrictedInfo ? null : $transaction->getPhone()->getCountry()->getPhoneCode();
        $this->response->phoneNumber = $isRestrictedInfo ? $this->getRestrictedPhoneNumber($transaction) : $transaction->getPhone()->getPhoneNumber();
        $this->response->transactionStatus = $transaction->getStatus();
        $this->response->isSuccess = $isSuccess;
        $this->response->nextWebPage = $nextWebPage;
        $this->response->error = $error;
        
        return $this->response;
    }
    
    public function resetResponse(): void
    {
        $this->response = TransactionResponse::generateAnEmptyObject();
    }
    
    private function getRestrictedEmail(Transaction $transaction): array
    {
        $email = $transaction->getUser()->getEmail();
        return '***'.substr($email, -(int)(strlen($email) / 2));
    }
    
    protected function getRestrictedPhoneNumber(Transaction $transaction): array
    {
        $phoneNumber = $transaction->getPhone()->getPhoneNumber();
        return '***'.substr($phoneNumber, -(int)(strlen($phoneNumber) / 2));
    }
}
