<?php

declare(strict_types=1);

namespace Homeapp\FilteringRepository;

use Homeapp\Filter\DTO\Filter;
use Homeapp\FilteringRepository\Filter as FilteringService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Vasil "coylOne" Kulakov <kulakov@vasiliy.pro>
 */
class FilteringRepository extends ServiceEntityRepository
{
    /**
     * @var FilteringService
     */
    protected FilteringService $filter;

    public function __construct(ManagerRegistry $registry, $entityClass, FilteringService $filter)
    {
        parent::__construct($registry, $entityClass);
        $this->filter = $filter;
    }

    public function filter(Filter $filter, $qb = null): array
    {
        /** @var QueryBuilder */
        $qb = $qb ?? $this->createQueryBuilder('a');

        $this->filter->prepareFilter($filter, $qb);

        if (!empty($filter->getCount()) && !empty($filter->getPage())) {
            $start = ($filter->getPage() - 1) * $filter->getCount();
            $qb->setFirstResult($start)->setMaxResults($filter->getCount());
        }

        return $qb->getQuery()->getResult();
    }

    public function filteredCount(?Filter $filter, $qb = null): int
    {
        $qb = $qb ?? $this->createQueryBuilder('a');

        if (null !== $filter) {
            $this->filter->prepareFilter($filter, $qb, true);
        }

        $qb->select('COUNT(DISTINCT(a.id)) as c');

        return (int)$qb->getQuery()->getSingleScalarResult();
    }
}
