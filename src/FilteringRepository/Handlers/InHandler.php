<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\In;

class InHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof In;
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($field instanceof In) {
            $qb->andWhere(sprintf('%s.%s IN (:%sIn)', FilteringHandlerInterface::DEFAULT_ALIAS, $field->getName(), FilteringHandlerInterface::DEFAULT_ALIAS.$field->getName()))
               ->setParameter(sprintf('%sIn', FilteringHandlerInterface::DEFAULT_ALIAS.$field->getName()), $field->getValues());
        }
    }
}
