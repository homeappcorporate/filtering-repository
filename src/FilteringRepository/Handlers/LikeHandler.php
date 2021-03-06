<?php

declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;


use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\Like;

class LikeHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof Like;
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($this->isSupported($field)) {
            $value = trim($field->getValue(), '%');
            $value = str_replace('%', '\\%', $value);
            $value = '%' . $value . '%';
            $qb->andWhere(sprintf(FilteringHandlerInterface::DEFAULT_ALIAS . '.%s LIKE :%sEq', $field->getName(), FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()))
                ->setParameter(sprintf('%sEq', FilteringHandlerInterface::DEFAULT_ALIAS . $field->getName()), $value);
        }
    }
}
