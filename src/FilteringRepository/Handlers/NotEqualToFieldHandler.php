<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;


use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\NotEqualToField;

class NotEqualToFieldHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof NotEqualToField;
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($field instanceof NotEqualToField) {
            $qb->andWhere(sprintf('%s.%s <> %s', FilteringHandlerInterface::DEFAULT_ALIAS, $field->getName(), $field->getValue()));
        }
    }
}
