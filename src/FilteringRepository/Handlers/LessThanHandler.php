<?php

declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\LessThan;

class LessThanHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof LessThan;
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($this->isSupported($field)) {
            $qb->andWhere(sprintf(FilteringHandlerInterface::DEFAULT_ALIAS . '.%s < :%sLt', $field->getName(), FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()))
                ->setParameter(sprintf('%sLt', FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()), $field->getValue());
        }
    }
}
