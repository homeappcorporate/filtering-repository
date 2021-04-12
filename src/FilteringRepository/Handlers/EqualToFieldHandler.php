<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\EqualToField;
use Homeapp\Filter\DTO\Field\FilterField;

class EqualToFieldHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof EqualToField;
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($field instanceof EqualToField) {
            $qb->andWhere(sprintf('%s.%s = %s', FilteringHandlerInterface::DEFAULT_ALIAS, $field->getName(), $field->getValue()));
        }
    }
}
