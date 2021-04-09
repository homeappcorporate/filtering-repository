<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\FromTo;
use Doctrine\ORM\QueryBuilder;

class FromToHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof FromTo;
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($field instanceof FromTo) {
            if (null !== $field->getFrom()) {
                $qb->andWhere(sprintf(FilteringHandlerInterface::DEFAULT_ALIAS . '.%s >= :%sFrom', $field->getName(), FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()))
                   ->setParameter(sprintf('%sFrom', FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()), $field->getFrom());
            }
            if (null !== $field->getTo()) {
                $qb->andWhere(sprintf(FilteringHandlerInterface::DEFAULT_ALIAS . '.%s <= :%sTo', $field->getName(), FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()))
                   ->setParameter(sprintf('%sTo', FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()), $field->getTo());
            }
        }
    }
}
