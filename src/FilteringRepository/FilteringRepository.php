<?php

declare(strict_types=1);

namespace Homeapp\FilteringRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Homeapp\Filter\DTO\Filter;
use Homeapp\Filter\DTO\Filter as FilterData;
use Homeapp\Filter\DTO\Sorting;
use Homeapp\FilteringRepository\Handlers\FilteringHandlerInterface;

/**
 * @author Vasil "coylOne" Kulakov <kulakov@vasiliy.pro>
 */
class FilteringRepository extends ServiceEntityRepository
{
    private array $allowedFields = [];
    private array $sortingFields = [];

    /** @var FilteringHandlerInterface[] */
    private array $handlers = [];

    /**
     * @psalm-suppress ArgumentTypeCoercion
     * @param FilteringHandlerInterface[] $handlers
     */
    public function __construct(ManagerRegistry $registry, string $entityClass, array $handlers)
    {
        parent::__construct($registry, $entityClass);
        $this->setHandlers($handlers);
    }

    public function setAllowedFields(array $allowedFields): void
    {
        $allowedFields = array_filter($allowedFields, fn($el) => is_string($el));
        $this->allowedFields = $allowedFields;
    }

    public function setSortingFields(array $sortingFields): void
    {
        $sortingFields = array_filter($sortingFields, fn($el) => is_string($el));
        $this->sortingFields = $sortingFields;
    }

    public function addSortingField(string $name): void
    {
        $this->sortingFields[] = $name;
    }

    public function addAllowedField(string $name): void
    {
        $this->allowedFields[] = $name;
    }

    /**
     * @param FilteringHandlerInterface[] $handlers
     */
    public function setHandlers(array $handlers): void
    {
        $handlers = array_filter($handlers, fn($el) => $el instanceof FilteringHandlerInterface);
        $this->handlers = $handlers;
    }

    public function addHandler(FilteringHandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function filter(Filter $filter, QueryBuilder $qb = null): array
    {
        $qb = $qb ?? $this->createQueryBuilder('a');

        $this->prepareFilter($filter, $qb);

        $count = $filter->getCount();
        if (null !== $count && !empty($filter->getPage())) {
            $start = ($filter->getPage() - 1) * $count;
            $qb->setFirstResult($start)->setMaxResults($count);
        }

        $result = $qb->getQuery()->getResult();
        if (!is_array($result)) {
            throw new \RuntimeException('Wrong result type');
        }

        return $result;
    }

    public function filteredCount(?Filter $filter, QueryBuilder $qb = null): int
    {
        $qb = $qb ?? $this->createQueryBuilder('a');

        if (null !== $filter) {
            $this->prepareFilter($filter, $qb, true);
        }

        $qb->select('COUNT(DISTINCT(a.id)) as c');

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function prepareFilter(FilterData $filter, QueryBuilder $queryBuilder, bool $noSort = false): void
    {
        $fields = $filter->getFields();
        foreach ($fields as $field) {
            $name = $field->getName();
            if (!in_array($name, $this->allowedFields, true)) {
                continue;
            }
            foreach ($this->handlers as $handler) {
                if (!$handler->isSupported($field)) {
                    continue;
                }

                $handler->addFilter($field, $queryBuilder);
                if ($handler->isFinal()) {
                    break;
                }
            }
        }

        if ($noSort) {
            return;
        }

        $sorting = $filter->getSorting();
        if (null !== $sorting && $this->isAllowedForSorting($sorting)) {
            $queryBuilder->orderBy('a.'.$sorting->getType(), $sorting->getDirection());
        }
    }

    private function isAllowedForSorting(Sorting $sorting): bool
    {
        if (empty($sorting->getType())) {
            return false;
        }

        return in_array($sorting->getType(), $this->sortingFields, true);
    }
}
