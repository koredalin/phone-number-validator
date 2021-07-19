<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Interfaces\PhoneConfirmationRepositoryInterface;
use App\Entities\PhoneConfirmation;
use App\Entities\Transaction;

/**
 * Description of PhoneConfirmationRepository
 *
 * @author Hristo
 */
final class PhoneConfirmationRepository implements PhoneConfirmationRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    
    private PhoneConfirmation $newPhoneConfirmation;
    
    public function __construct(EntityManagerInterface $em, PhoneConfirmation $newPhoneConfirmation)
    {
        $this->em = $em;
        $this->objectRepository = $this->em->getRepository(PhoneConfirmation::class);
        $this->newPhoneConfirmation = $newPhoneConfirmation;
    }
    
    
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): PhoneConfirmation
    {
        $serializedNewObj = \serialize($this->newPhoneConfirmation);
        
        return \unserialize($serializedNewObj);
    }
    
    public function findOneById(int $id): PhoneConfirmation
    {
        return $this->objectRepository->find($id);
    }
    
    public function findLastByTransactionAwaitingStatus(Transaction $transaction): ?PhoneConfirmation
    {
        $result = $this->objectRepository->findBy(
            ['transaction_id' => $transaction->id, 'status' => PhoneConfirmation::STATUS_AWAITING_REQUEST],
            ['id' => 'DESC'],
            1
        );
        if (isset($result[0])) {
            return $result[0];
        }
        
        return null;
    }
    
    public function save(PhoneConfirmation $phoneConfirmation): void
    {
        $this->objectRepository->persist($phoneConfirmation);
        $this->objectRepository->flush();
    }
}
