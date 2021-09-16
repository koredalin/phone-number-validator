<?php

namespace App\Controllers\Response;

use App\Controllers\Response\Interfaces\ResponseAssembleInterface;
use App\Controllers\Response\Models\TransactionSubmitResult as TransactionResponse;
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
        string $error = '',
        bool $isRestrictedInfo = true,
        string $nextWebPage = ''
    ): TransactionResponse {
        if (is_null($transaction)) {
            $this->resetResponse();
            $this->response->isSuccess = false;
            $this->response->error = $error;
            $this->response->nextWebPage = $nextWebPage;
            return $this->response;
        }
        
        $this->response->transactionId = $transaction->getId();
        $this->response->email = $isRestrictedInfo ? $this->getRestrictedEmail($transaction) : $transaction->getUser()->getEmail();
        $this->response->phoneCode = $isRestrictedInfo ? null : $transaction->getPhone()->getCountry()->getPhoneCode();
        $this->response->phoneNumber = $isRestrictedInfo ? $this->getRestrictedPhoneNumber($transaction) : $transaction->getPhone()->getPhoneNumber();
        $this->response->transactionStatus = $transaction->getStatus();
        $this->response->transactionConfirmedAt = $transaction->getConfirmedAt();
        $this->response->error = $error;
        $this->response->nextWebPage = $nextWebPage;
        
        return $this->response;
    }
    
    public function resetResponse(): void
    {
        $this->response = TransactionResponse::generateAnEmptyObject();
    }
    
    private function getRestrictedEmail(Transaction $transaction): string
    {
        $emailArr = explode('@', $transaction->getUser()->getEmail());
        if (count($emailArr) != 2) {
            return '';
        }
        
        return '***'.substr($emailArr[0], -(int)(strlen($emailArr[0]) / 2)).'@'.$emailArr[1];
    }
    
    protected function getRestrictedPhoneNumber(Transaction $transaction): string
    {
        $phoneNumber = $transaction->getPhone()->getPhoneNumber();
        
        return '***'.substr($phoneNumber, -(int)ceil(strlen($phoneNumber) / 2));
    }
}
