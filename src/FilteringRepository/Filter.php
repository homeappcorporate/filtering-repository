<?php

declare(strict_types=1);

namespace Homeapp\FilteringRepository;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Filter as FilterData;
use Homeapp\FilteringRepository\Handlers\FilteringHandlerInterface;

class Filter
{
    protected array $allowedFields = [];
    protected array $virtualFields = [];
    protected array $sortingFields = [];

    /** @var FilteringHandlerInterface[] */
    protected array $handlers;

    public function __construct(FilteringHandlerInterface ...$handlers)
    {
        $this->handlers = $handlers;
    }
    public function prepareFilter(FilterData $filter, QueryBuilder $queryBuilder, bool $noSort = false): void
    {
        $fields = $filter->getFields();
        foreach ($fields as $field) {
            $name = $field->getName();
            if (!in_array($name, $this->allowedFields, true) && !in_array($name, $this->virtualFields, true)) {
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
        if ($sorting && !empty($sorting->getType())) {
            if (in_array($sorting->getType(), $this->sortingFields, true)) {
                $queryBuilder->orderBy('a.' . $sorting->getType(), $sorting->getDirection());
            }
        }
    }
}
