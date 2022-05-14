<?php

namespace Accounting\Service\Transaction;

use Accounting\Entity\Transaction;
use Accounting\Entity\User;
use Accounting\Repository\UserRepository;
use Accounting\Service\Balance\BalanceService;
use Accounting\Service\Cost\TaskCostService;
use Doctrine\ORM\EntityManagerInterface;

class TaskTransactionService
{
    private TaskCostService $costService;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private BalanceService $balanceService;

    public function __construct(
        TaskCostService $costService,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        BalanceService $balanceService
    ) {
        $this->costService = $costService;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->balanceService = $balanceService;
    }

    public function createAssignedTaskTransaction(array $data): void
    {
        $user = $this->userRepository->findOneBy([
            'publicId' => $data['assignee'],
        ]);

        $transaction = new Transaction();
        $transaction->setAmount($this->costService->getTaskCost());
        $transaction->setUser($user);
        $transaction->setMeta([
            'task_public_id' => $data['public_id'],
            'task_name' => $data['name'] ?? '',
        ]);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        $this->balanceService->updateBalance($user, $transaction);
    }

    public function createCompletedTaskTransaction(array $data): void
    {
        $user = $this->userRepository->findOneBy([
            'publicId' => $data['completed_by'],
        ]);

        $transaction = new Transaction();
        $transaction->setAmount($this->costService->getCompletedTaskCost());
        $transaction->setUser($user);
        $transaction->setMeta([
            'task_public_id' => $data['public_id'],
        ]);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        $this->balanceService->updateBalance($user, $transaction);
    }

    public function createTopUpTransaction(User $user, string $amount): void
    {
        $transaction = new Transaction();
        $transaction->setType(Transaction::TYPE_TOP_UP);
        $transaction->setAmount(bcmul($amount, '-1', '3'));
        $transaction->setUser($user);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        $this->balanceService->updateBalance($user, $transaction);

        // send notification event
    }
}
