<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Homeapp\Filter\DTO\Field\LessThan;
use Homeapp\Filter\DTO\Field\FilterField;
use Doctrine\ORM\QueryBuilder;

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
        if ($field instanceof LessThan) {
            $qb->andWhere(sprintf(FilteringHandlerInterface::DEFAULT_ALIAS.'.%s < :%sLt', $field->getName(), FilteringHandlerInterface::DEFAULT_ALIAS.$field->getName()))
               ->setParameter(sprintf('%sLt', FilteringHandlerInterface::DEFAULT_ALIAS.$field->getName()), $field->getValue());
        }
    }
}
