<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\IsNotNull;
use Homeapp\Filter\DTO\Field\IsNull;

class NullHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof IsNotNull || $field instanceof IsNull;
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($field instanceof IsNull) {
            $qb->andWhere(sprintf(FilteringHandlerInterface::DEFAULT_ALIAS.'.%s IS NULL', $field->getName()));

            return;
        }

        if ($field instanceof IsNotNull) {
            $qb->andWhere(sprintf(FilteringHandlerInterface::DEFAULT_ALIAS.'.%s IS NOT NULL', $field->getName()));

            return;
        }
    }
}
