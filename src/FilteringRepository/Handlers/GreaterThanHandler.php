<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Homeapp\Filter\DTO\Field\GreaterThan;
use Homeapp\Filter\DTO\Field\FilterField;
use Doctrine\ORM\QueryBuilder;

class GreaterThanHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof GreaterThan;
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($field instanceof GreaterThan) {
            $qb->andWhere(sprintf(FilteringHandlerInterface::DEFAULT_ALIAS . '.%s > :%sGt', $field->getName(), FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()))
               ->setParameter(sprintf('%sGt', FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()), $field->getValue());
        }
    }
}
