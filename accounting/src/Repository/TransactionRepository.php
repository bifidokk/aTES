<?php

namespace Accounting\Repository;

use Accounting\Entity\Transaction;
use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository
{
    public function calculateDailyEarnings()
    {
        $dateFrom = new \DateTime();
        $dateTo = clone $dateFrom;

        $dateFrom->setTime(0, 0, 0);
        $dateTo->setTime(23, 59, 59);

        return $this
            ->createQueryBuilder('t')
            ->select('
                SUM(CASE WHEN t.status = :completed AND t.type = :deposit AND cast(t.amount as decimal) < 0 THEN cast(t.amount as decimal) ELSE 0 END) as deposit,
                SUM(CASE WHEN t.status = :completed AND t.type = :top_up AND cast(t.amount as decimal) > 0 THEN cast(t.amount as decimal) ELSE 0 END) as top_up
            ')
            ->andWhere('t.status = :completed')
            ->andWhere('t.createdAt >= :date_from')
            ->andWhere('t.createdAt <= :date_to')
            ->setParameters([
                'completed' => Transaction::STATUS_COMPLETED,
                'deposit' => Transaction::TYPE_DEPOSIT,
                'top_up' => Transaction::TYPE_TOP_UP,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ])
            ->getQuery()
            ->getResult();
    }
}
