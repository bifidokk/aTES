<?php

namespace Accounting\Controller;

use Accounting\Repository\TransactionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
class DashboardController
{
    private TransactionRepository $transactionRepository;

    public function __construct(
        TransactionRepository $transactionRepository
    ) {
        $this->transactionRepository = $transactionRepository;
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/dashboard/daily_earnings', name: 'dashboard_daily_earnings')]
    public function getDailyEarnings(): JsonResponse
    {
        $data = $this->transactionRepository->calculateDailyEarnings()[0];
        $data['balance'] = $data['deposit_withdraw'] + $data['deposit_refund'];

        return new JsonResponse($data);
    }
}
