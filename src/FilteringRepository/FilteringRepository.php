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

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function __construct(ManagerRegistry $registry, string $entityClass, FilteringService $filter)
    {
        parent::__construct($registry, $entityClass);
        $this->filter = $filter;
    }

    public function filter(Filter $filter, QueryBuilder $qb = null): array
    {
        $qb = $qb ?? $this->createQueryBuilder('a');

        $this->filter->prepareFilter($filter, $qb);

        $count = $filter->getCount();
        if (null !== $count && !empty($filter->getPage())) {
            $start = ($filter->getPage() - 1) * $count;
            $qb->setFirstResult($start)->setMaxResults($count);
        }

        $result = $qb->getQuery()->getResult();
        if (!is_array($result)){
            throw new \RuntimeException('Wrong result type');
        }
        return $result;
    }

    public function filteredCount(?Filter $filter, QueryBuilder $qb = null): int
    {
        $qb = $qb ?? $this->createQueryBuilder('a');

        if (null !== $filter) {
            $this->filter->prepareFilter($filter, $qb, true);
        }

        $qb->select('COUNT(DISTINCT(a.id)) as c');

        return (int)$qb->getQuery()->getSingleScalarResult();
    }
}
