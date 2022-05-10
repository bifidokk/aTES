<?php

namespace Accounting\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
class TransactionController
{
    #[Route('/api/transactions', name: 'transaction_list')]
    public function transactionList(Request $request): JsonResponse
    {
        return new JsonResponse([]);
    }
}
