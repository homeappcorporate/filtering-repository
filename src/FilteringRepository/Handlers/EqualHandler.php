<?php

declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Homeapp\Filter\DTO\Field\Equal;
use Homeapp\Filter\DTO\Field\FilterField;
use Doctrine\ORM\QueryBuilder;

class EqualHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof Equal;
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($field instanceof Equal) {
            $qb->andWhere(
                sprintf(
                    FilteringHandlerInterface::DEFAULT_ALIAS . '.%s = :%sEq',
                    $field->getName(),
                    FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()
                )
            )->setParameter(
                sprintf(
                    '%sEq',
                    FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()
                ),
                $field->getValue()
            );
        }
    }
}
