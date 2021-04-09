<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;


use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\NotEqual;
use Doctrine\ORM\QueryBuilder;

class NotEqualHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof NotEqual;
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($field instanceof NotEqual) {
            $qb->andWhere(sprintf(FilteringHandlerInterface::DEFAULT_ALIAS . '.%s <> :%sNeq', $field->getName(), FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()))
               ->setParameter(sprintf('%sNeq', FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()), $field->getValue());
        }
    }
}
